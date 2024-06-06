<?php

namespace App\Actions;

use App\Models\Chat;
use Illuminate\Http\JsonResponse;

class GetMessageAction
{

    public function handle(Chat $chat): JsonResponse
    {
        $messages = $chat->messages()
            ->select(['id as messageId', 'user_id', 'created_at as timestamp', 'message as text'])
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(2)
            ->toArray();

        $messages['data'] = collect($messages['data'])
            ->map(function ($item) {
                unset($item['user_id']);
                return $item;
            })
            ->toArray();

        return returnJson(true, $messages);
    }

}
