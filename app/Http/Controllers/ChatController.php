<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function createChat(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator($request->all(), ['userId' => 'required|integer|exists:users,id']);

        if ($validator->fails()) {
            return $this->returnJson(status: false, errors: $validator->errors()->toArray(), code: 400);
        }

        $currentUser = auth()->id();
        $newUser = $request->input('userId');

        $chat = Chat::whereHas('users', static function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser);
        })->whereHas('users', function ($query) use ($newUser) {
            $query->where('user_id', $newUser);
        })->first();

        if ($chat) {
            return $this->returnJson(true, ['chatId' => $chat->id]);
        }

        $chat = Chat::create();
        $chat->users()->attach([$currentUser, $newUser]);

        return $this->returnJson(true, ['chatId' => $chat->id]);
    }

    public function listChats(): \Illuminate\Http\JsonResponse
    {
        $currentUser = auth()->id();
        $chats = Chat::whereHas('users', static function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser);
        })
            ->select(['id as chatId'])
            ->get()
            ->toArray();

        return $this->returnJson(true, $chats);
    }

    public function sendMessage(Chat $chat, Request $request): \Illuminate\Http\JsonResponse
    {
        $chat->messages()->create([
          'user_id' => auth()->id(),
          'message' => $request->input('message'),
        ]);

        return $this->returnJson(true);
    }

    public function getMessages(Chat $chat): \Illuminate\Http\JsonResponse
    {
        $messages = $chat->messages()
            ->select(['id as messageId', 'user_id', 'created_at as timestamp', 'message as text'])
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(2)
            ->toArray();

        $messages['data'] = collect($messages['data'])
            ->map(function ($item){
               unset($item['user_id']);
               return $item;
            })
            ->toArray();

        return $this->returnJson(true, $messages);
    }
}
