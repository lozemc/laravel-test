<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], static function () {
    Route::post('users', [UserController::class, 'createUser']);
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
        Route::get('users', [UserController::class, 'listUsers']);

        Route::post('chats', [ChatController::class, 'createChat']);
        Route::post('chats/{chat}/messages', [ChatController::class, 'sendMessage']);

        Route::get('chats', [ChatController::class, 'listChats']);
        Route::get('chats/{chat}/messages', [ChatController::class, 'getMessages']);
    });
});
