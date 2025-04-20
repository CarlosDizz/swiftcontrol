<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tickets = Ticket::where('owner_id', $user->id)
            ->orWhere(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->whereNull('owner_id');
            })
            ->with(['event', 'priceRange']) // relaciones para enriquecer la respuesta
            ->get();

        return response()->json($tickets);
    }


    public function checkTicket(Request $request)
    {
        $userRole = optional(auth()->user()->role)->name;

        if (!in_array($userRole, ['ticket_checker', 'admin'])) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'token' => 'required|uuid',
            'event_id' => 'required|exists:events,id',
        ]);

        $ticket = Ticket::where('token', $request->token)
            ->where('event_id', $request->event_id)
            ->first();

        if (!$ticket) {
            return response()->json(['message' => 'Entrada no válida.'], 404);
        }

        if ($ticket->checked_in) {
            return response()->json(['message' => 'Entrada ya usada.'], 400);
        }

        // Guardamos el check-in
        $ticket->checked_in = true;

        // Actualizamos el campo JSON `data`
        $ticket->data = array_merge($ticket->data ?? [], [
            'checked_in' => [
                'user_id' => auth()->id(),
                'timestamp' => now()->toDateTimeString(),
            ]
        ]);

        $ticket->save();

        return response()->json(['message' => 'Entrada validada con éxito.']);
    }

    public function transferTicket(Request $request)
    {
        $request->validate([
            'token' => 'required|uuid',
            'email' => 'required|email',
        ]);

        $user = $request->user();

        $ticket = Ticket::where('token', $request->token)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereNull('owner_id')->where('buyer_id', $user->id);
                    });
            })
            ->first();

        if (! $ticket) {
            return response()->json(['message' => 'No tienes permiso para transferir esta entrada.'], 403);
        }

        if ($ticket->checked_in) {
            return response()->json(['message' => 'No se puede transferir una entrada que ya ha sido usada.'], 400);
        }

        $recipient = User::where('email', $request->email)->first();

        $ticket->data = array_merge($ticket->data ?? [], [
            'transferred' => [
                'from_user_id' => $user->id,
                'to_email' => $request->email,
                'timestamp' => now(),
            ],
        ]);

        if ($recipient) {
            $ticket->owner_id = $recipient->id;
            $ticket->owner_email = null;
            $message = 'Entrada transferida al usuario registrado.';
        } else {
            $ticket->owner_id = null;
            $ticket->owner_email = $request->email;
            $message = 'Entrada transferida. El destinatario debe registrarse para recibirla.';
        }

        $ticket->save();

        return response()->json([
            'message' => $message,
            'ticket' => $ticket,
        ]);
    }

    public function recoverTicket(Request $request)
    {
        $request->validate([
            'token' => 'required|uuid',
        ]);

        $user = auth()->user();

        $ticket = Ticket::where('token', $request->token)
            ->where('buyer_id', $user->id)
            ->whereNull('owner_id')
            ->first();

        if (! $ticket) {
            return response()->json(['message' => 'No tienes permiso para recuperar esta entrada.'], 403);
        }


        $data = json_decode($ticket->data, true) ?? [];

        if (isset($data['checkin'])) {
            return response()->json(['message' => 'La entrada ya ha sido utilizada y no puede ser recuperada.'], 400);
        }


        $ticket->owner_email = null;
        $ticket->transferred_at = null;
        $ticket->revoked_at = now();


        $data['revoked'] = [
            'by_user_id' => $user->id,
            'at' => now()->toDateTimeString(),
        ];
        $ticket->data = $data;

        $ticket->save();

        return response()->json([
            'message' => 'Entrada recuperada correctamente.',
            'ticket' => $ticket,
        ]);
    }




}

