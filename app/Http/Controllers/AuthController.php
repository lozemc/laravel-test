<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
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
