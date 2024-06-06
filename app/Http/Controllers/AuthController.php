<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Авторизация
     * @bodyParam email string required E-mail пользователя. Example: example@gmail.com
     * @bodyParam password string required Пароль пользователя. Example: s7Sjkd6fg230d
     * @response {"status": true,"response": {"accessToken": "1|g2YrOcIVUWaI3JHinARFSYiFj4YCJyIxWp14JO2B024b58s9"}}
     * @response 401 {"status": false, "errors": "Email & Password does not match with our record"}
     */
    public function login(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->returnJson(status: false, errors: $validator->messages()->all(), code: 401);
        }

        if (Auth::attempt($request->only(['email', 'password']), true)) {
            $user = $request->user();
            $user->tokens()->delete();

            return $this->returnJson(
                true,
                ['accessToken' => $user->createToken('accessToken')->plainTextToken]
            );
        }

        return $this->returnJson(
            status: false,
            errors: 'Email & Password does not match with our record',
            code: 401
        );
    }
}
