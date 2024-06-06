<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    public function auth($request): JsonResponse
    {
        $validate = $this->validate($request->all());

        if ($validate['status'] === true) {
            if (Auth::attempt($request->only(['email', 'password']), true)) {
                $user = $request->user();
                $user->tokens()->delete();

                return returnJson(
                    true,
                    ['accessToken' => $user->createToken('accessToken')->plainTextToken]
                );
            }

            return returnJson(
                status: false,
                errors: 'Email & Password does not match with our record',
                code: 401
            );
        }

        return returnJson(status: false, errors: $validate['errors'], code: 400);
    }

    private function validate($request): array
    {
        $validator = validator($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->toArray()];
        }

        return ['status' => true];
    }

}
