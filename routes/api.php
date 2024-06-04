<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], static function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::any('{any}', static function () {
    return response()->json(['status' =>false, 'errors' => 'Method not found'], 400);
})->where('any', '.*');
