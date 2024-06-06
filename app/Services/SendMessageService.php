<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Http\JsonResponse;

class SendMessageService
{

    public function sendMessage(Chat $chat, $request): JsonResponse
    {
        $validate = $this->validate($request);

        if ($validate['status'] === true) {
            $chat->messages()->create([
                'user_id' => auth()->id(),
                'message' => $request->input('message'),
            ]);

            return returnJson(true);
        }

        return returnJson(status: false, errors: $validate['errors'], code: 400);
    }

    private function validate($request): array
    {
        $validator = validator($request->all(), ['message' => 'required|string']);

        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->toArray()];
        }

        return ['status' => true];
    }

}
