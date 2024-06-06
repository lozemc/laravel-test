<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateUserService
{

    public function createUser(Request $request): JsonResponse
    {
        $validate = $this->validate($request->all());
        if ($validate['status'] === true) {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
            ]);

            return returnJson(true, ['accessToken' => $user->createToken('accessToken')->plainTextToken]);
        }

        return returnJson(status: false, errors: $validate['errors'], code: 400);
    }

    private function validate($request): array
    {
        $validator = validator($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->toArray()];
        }

        return ['status' => true];
    }
}
