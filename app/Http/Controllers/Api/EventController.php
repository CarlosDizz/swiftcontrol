<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // GET /api/events
    public function index(Request $request)
    {
        $query = Event::query();

        // 🔍 Filtros opcionales
        if ($request->filled('from')) {
            $query->where('date', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->where('date', '<=', $request->input('to'));
        }

        if ($request->boolean('withPrices')) {
            $query->with('priceRanges');
        }

        return response()->json($query->get());
    }

    // GET /api/events/{id}
    public function show(Event $event)
    {
        $event->load('priceRanges');

        return response()->json($event);
    }
}
