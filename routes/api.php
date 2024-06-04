<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], static function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {

        Route::get('users/all', [UserController::class, 'getAllUsers']);

    });
});

Route::any('{any}', static function () {
    return response()->json(['status' =>false, 'errors' => 'Method not found'], 400);
})->where('any', '.*');
