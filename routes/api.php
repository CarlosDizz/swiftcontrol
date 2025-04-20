<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\MediaFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', function (Request $request) {
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => '1'
    ]);

    return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
});

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Incorrect login'], 401);
    }

    return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/ping', function () {
    return response()->json(['pong' => "Probando despliegues desde mac"]);
});

use App\Http\Controllers\Api\EventController;

Route::middleware('auth:sanctum')->prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('{event}', [EventController::class, 'show']);
});




Route::middleware(['auth:sanctum', 'role:organizer'])->get('/organizer-area', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'role' => $request->user()->role->name,
    ]);
});

Route::middleware('auth:sanctum')->get('/media/{id}', function (string $id) {
    $file = MediaFile::findOrFail($id);

    if (!Storage::exists($file->path)) {
        abort(404);
    }

    return Response::make(Storage::get($file->path), 200, [
        'Content-Type' => $file->mime_type,
        'Content-Disposition' => 'inline; filename="'.$file->filename.'"',
    ]);
});


use App\Http\Controllers\TicketController;

Route::middleware('auth:sanctum')->get('/tickets', [TicketController::class, 'index']);

Route::middleware(['auth:sanctum'])->post('/tickets/check', [TicketController::class, 'checkTicket']);

Route::middleware('auth:sanctum')->post('/tickets/transfer', [TicketController::class, 'transferTicket']);

Route::middleware('auth:sanctum')->post('/tickets/recover', [TicketController::class, 'recoverTicket']);



