<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Авторизация
     * @bodyParam email string required E-mail пользователя. Example: example@gmail.com
     * @bodyParam password string required Пароль пользователя. Example: s7Sjkd6fg230d
     * @response {"status": true,"response": {"accessToken": "1|g2YrOcIVUWaI3JHinARFSYiFj4YCJyIxWp14JO2B024b58s9"}}
     * @response 401 {"status": false, "errors": "Email & Password does not match with our record"}
     */
    public function login(Request $request, AuthService $service): JsonResponse
    {
        return $service->auth($request);
    }
}
