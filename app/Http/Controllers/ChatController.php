<?php

namespace App\Http\Controllers;

use App\Actions\GetMessageAction;
use App\Actions\ListChatsAction;
use App\Models\Chat;
use App\Services\CreateChatService;
use App\Services\SendMessageService;
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
    public function createChat(Request $request, CreateChatService $service): JsonResponse
    {
        return $service->createChat($request);
    }

    /**
     * Получение списка чатов
     * @authenticated
     * @response {"status":true,"response":{"current_page":1,"data":[{"chatId":2,"timestamp":1717617112,"chatName":"Чат с Alyson Monahan","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":3,"firstName":"Alyson","lastName":"Monahan","email":"271katlynn69@example.net"}]},{"chatId":1,"timestamp":1717617105,"chatName":"Чат с Audreanne Brekke","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":2,"firstName":"Audreanne","lastName":"Brekke","email":"294kris.cassandra@example.com"}]},{"chatId":2,"timestamp":1717617112,"chatName":"Чат с Alyson Monahan","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":3,"firstName":"Alyson","lastName":"Monahan","email":"271katlynn69@example.net"}]},{"chatId":1,"timestamp":1717617105,"chatName":"Чат с Audreanne Brekke","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":2,"firstName":"Audreanne","lastName":"Brekke","email":"294kris.cassandra@example.com"}]},{"chatId":1,"timestamp":1717617105,"chatName":"Чат с Audreanne Brekke","users":[{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"},{"id":2,"firstName":"Audreanne","lastName":"Brekke","email":"294kris.cassandra@example.com"}]}],"first_page_url":"http://localhost/api/v1/chats?page=1","from":1,"last_page":1,"last_page_url":"http://localhost/api/v1/chats?page=1","links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://localhost/api/v1/chats?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"next_page_url":null,"path":"http://localhost/api/v1/chats","per_page":20,"prev_page_url":null,"to":5,"total":5}}
     */
    public function listChats(ListChatsAction $action): JsonResponse
    {
        return returnJson(true, $action->handle());
    }

    /**
     * Отправка сообщений
     * @authenticated
     * @UrlParam chat_id int required Идентификатор чата. Example: 2
     * @bodyParam message string required Текст сообщения. Example: Text message
     * @response {"status":true}
     * @response 400 {"status":false,"errors":{"message":["The message field is required."]}}
     */
    public function sendMessage(Chat $chat, Request $request, SendMessageService $service): JsonResponse
    {
        return $service->sendMessage($chat, $request);
    }

    /**
     * Получение списка сообщений
     * @authenticated
     * @UrlParam chat_id int required Идентификатор чата. Example: 2
     * @response {"status":true,"response":{"current_page":1,"data":[{"messageId":5,"timestamp":1717652259,"text":"Text message","user":{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"}},{"messageId":3,"timestamp":1717617341,"text":"three","user":{"id":1,"firstName":"Ivanov","lastName":"Ivan","email":"ivan@ya.ru"}}],"first_page_url":"http://localhost/api/v1/chats/2/messages?page=1","from":1,"last_page":1,"last_page_url":"http://localhost/api/v1/chats/2/messages?page=1","links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://localhost/api/v1/chats/2/messages?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"next_page_url":null,"path":"http://localhost/api/v1/chats/2/messages","per_page":2,"prev_page_url":null,"to":2,"total":2}}
     */
    public function getMessages(Chat $chat, GetMessageAction $action): JsonResponse
    {
        return $action->handle($chat);
    }
}
