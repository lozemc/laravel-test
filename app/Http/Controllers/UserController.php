<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function listUsers(): \Illuminate\Http\JsonResponse
    {
        $users = User::select(['id as userId', 'email', 'firstName', 'lastName'])
            ->paginate(20);

        return $this->returnJson(true, $users->toArray());
    }
}
