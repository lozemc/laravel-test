<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Создание чата с пользователем
     * @authenticated
     * @bodyParam userId string required Идентификатор чата собеседника. Example: 5
     * @response {"status": true,"data": {"chatId": 123}}
     * @response 400 {"status":false,"errors":{"userId":["The selected user id is invalid."]}}
     */
    public function createChat(Request $request): JsonResponse
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

    /**
     * Получение списка чатов
     * @authenticated
     * @response {"status":true,"response":{"current_page":1,"data":[{"chatId":2,"timestamp":1717617112,"chatName":"Чат с Alyson Monahan","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":3,"firstName":"Alyson","lastName":"Monahan","email":"271katlynn69@example.net"}]},{"chatId":1,"timestamp":1717617105,"chatName":"Чат с Audreanne Brekke","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":2,"firstName":"Audreanne","lastName":"Brekke","email":"294kris.cassandra@example.com"}]},{"chatId":2,"timestamp":1717617112,"chatName":"Чат с Alyson Monahan","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":3,"firstName":"Alyson","lastName":"Monahan","email":"271katlynn69@example.net"}]},{"chatId":1,"timestamp":1717617105,"chatName":"Чат с Audreanne Brekke","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":2,"firstName":"Audreanne","lastName":"Brekke","email":"294kris.cassandra@example.com"}]},{"chatId":1,"timestamp":1717617105,"chatName":"Чат с Audreanne Brekke","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":2,"firstName":"Audreanne","lastName":"Brekke","email":"294kris.cassandra@example.com"}]}],"first_page_url":"http://localhost/api/v1/chats?page=1","from":1,"last_page":1,"last_page_url":"http://localhost/api/v1/chats?page=1","links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://localhost/api/v1/chats?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"next_page_url":null,"path":"http://localhost/api/v1/chats","per_page":20,"prev_page_url":null,"to":5,"total":5}}
     */
    public function listChats(): JsonResponse
    {
        $currentUser = auth()->id();
        $chats = Chat::whereHas('users', function ($query) use ($currentUser) {
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

        return $this->returnJson(true, $chats);
    }

    /**
     * Отправка сообщений
     * @authenticated
     * @UrlParam chat_id int required Идентификатор чата. Example: 2
     * @bodyParam message string required Текст сообщения. Example: Text message
     * @response {"status":true}
     * @response 400 {"status":false,"errors":{"message":["The message field is required."]}}
     */
    public function sendMessage(Chat $chat, Request $request): JsonResponse
    {
        $validator = validator($request->all(), ['message' => 'required|string']);

        if ($validator->fails()) {
            return $this->returnJson(status: false, errors: $validator->errors()->toArray(), code: 400);
        }

        $chat->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->input('message'),
        ]);

        return $this->returnJson(true);
    }

    /**
     * Получение списка сообщений
     * @authenticated
     * @UrlParam chat_id int required Идентификатор чата. Example: 2
     * @response {"status":true,"response":{"current_page":1,"data":[{"messageId":5,"timestamp":1717652259,"text":"Text message","user":{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"}},{"messageId":3,"timestamp":1717617341,"text":"three","user":{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"}}],"first_page_url":"http://localhost/api/v1/chats/2/messages?page=1","from":1,"last_page":1,"last_page_url":"http://localhost/api/v1/chats/2/messages?page=1","links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://localhost/api/v1/chats/2/messages?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"next_page_url":null,"path":"http://localhost/api/v1/chats/2/messages","per_page":2,"prev_page_url":null,"to":2,"total":2}}
     */
    public function getMessages(Chat $chat): JsonResponse
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

        return $this->returnJson(true, $messages);
    }
}
