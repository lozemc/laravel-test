<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Http\JsonResponse;

class CreateChatService
{

    public function createChat($request): JsonResponse
    {
        $validate = $this->validate($request->all());
        if ($validate['status'] === true) {
            $currentUser = auth()->id();
            $newUser = $request->input('userId');

            $chat = Chat::whereHas('users', static function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser);
            })->whereHas('users', function ($query) use ($newUser) {
                $query->where('user_id', $newUser);
            })->first();

            if ($chat) {
                return returnJson(true, ['chatId' => $chat->id]);
            }

            $chat = Chat::create();
            $chat->users()->attach([$currentUser, $newUser]);

            return returnJson(true, ['chatId' => $chat->id]);
        }

        return returnJson(status: false, errors: $validate['errors'], code: 400);
    }

    private function validate($request): array
    {
        $validator = validator($request, ['userId' => 'required|integer|exists:users,id']);

        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->toArray()];
        }

        return ['status' => true];
    }

}
