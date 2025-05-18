<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/tickets', \App\Http\Controllers\TicketController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/ticket', \App\Http\Controllers\TicketController::class);
    Route::apiResource('/comment', \App\Http\Controllers\CommentController::class);
    Route::get('/users', [\App\Http\Controllers\ChatController::class, 'getUsers']);
    Route::post('/send-message', [\App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy']);
});
Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
