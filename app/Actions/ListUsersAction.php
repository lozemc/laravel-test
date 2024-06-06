<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class ListUsersAction
{

    public function handle(): JsonResponse
    {
        $users = User::select(['id as userId', 'email', 'firstName', 'lastName'])
            ->paginate(20);
        return returnJson(true, $users->toArray());
    }

}
