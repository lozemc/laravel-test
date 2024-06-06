<?php

namespace App\Actions;

use App\Models\Chat;

class ListChatsAction
{

    public function handle(): array
    {
        $currentUser = auth()->id();
        $chats = Chat::whereHas('users', static function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser);
        })
            ->with(['users', 'messages'])
            ->join('messages', 'chats.id', '=', 'messages.chat_id')
            ->orderBy('messages.created_at', 'desc')
            ->paginate(20, ['chats.*'])
            ->toArray();


        $data = collect($chats['data'])->map(function ($chat) use ($currentUser) {
            $otherUsers = collect($chat['users'])->filter(function ($user) use ($currentUser) {
                return $user['id'] !== $currentUser;
            });

            $chatName = 'Чат с ' . $otherUsers->map(function ($user) {
                    return $user['firstName'] . ' ' . $user['lastName'];
                })->implode(', ');

            $users = collect($chat['users'])->map(function ($item) {
                return collect($item)->except('pivot');
            });

            return [
                'chatId' => $chat['id'],
                'timestamp' => $chat['created_at'],
                'chatName' => $chatName,
                'users' => $users,
            ];
        });

        $chats['data'] = $data;

        return $chats;
    }

}
