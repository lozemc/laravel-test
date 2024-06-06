<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Получение списка пользователей
     * @authenticated
     * @response {"status":true,"response":{"current_page":1,"data":[{"userId":1,"email":"ivan@ya.ru","firstName":"Ivanov","lastName":"Ivan"},{"userId":2,"email":"294kris.cassandra@example.com","firstName":"Audreanne","lastName":"Brekke"},{"userId":3,"email":"271katlynn69@example.net","firstName":"Alyson","lastName":"Monahan"},{"userId":4,"email":"288zrutherford@example.com","firstName":"Katelin","lastName":"Krajcik"},{"userId":5,"email":"631abins@example.org","firstName":"Orrin","lastName":"Pagac"},{"userId":6,"email":"580davonte36@example.org","firstName":"Percival","lastName":"Heller"},{"userId":7,"email":"389boyle.judson@example.net","firstName":"Nicholas","lastName":"Hermann"},{"userId":8,"email":"756dickens.perry@example.net","firstName":"Ansley","lastName":"Lakin"},{"userId":9,"email":"805medhurst.asia@example.com","firstName":"Garnet","lastName":"Olson"},{"userId":10,"email":"296renee.hammes@example.net","firstName":"Ebony","lastName":"Kling"},{"userId":11,"email":"516blick.davion@example.org","firstName":"Arnold","lastName":"Sawayn"},{"userId":12,"email":"939sporer.ally@example.net","firstName":"Felix","lastName":"Kessler"},{"userId":13,"email":"707berta63@example.net","firstName":"Myrtis","lastName":"Rutherford"},{"userId":14,"email":"615mariane.ward@example.com","firstName":"Hardy","lastName":"Kozey"},{"userId":15,"email":"420harry31@example.net","firstName":"Ransom","lastName":"Heidenreich"},{"userId":16,"email":"732thomenick@example.net","firstName":"Hettie","lastName":"Bradtke"},{"userId":17,"email":"981yrippin@example.net","firstName":"Jake","lastName":"Klein"},{"userId":18,"email":"569dpfeffer@example.org","firstName":"Royce","lastName":"Koepp"},{"userId":19,"email":"800bell.schowalter@example.org","firstName":"Kay","lastName":"McDermott"},{"userId":20,"email":"402pabbott@example.net","firstName":"Marcia","lastName":"Marquardt"}],"first_page_url":"http://localhost/api/v1/users?page=1","from":1,"last_page":2,"last_page_url":"http://localhost/api/v1/users?page=2","links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http://localhost/api/v1/users?page=1","label":"1","active":true},{"url":"http://localhost/api/v1/users?page=2","label":"2","active":false},{"url":"http://localhost/api/v1/users?page=2","label":"Next &raquo;","active":false}],"next_page_url":"http://localhost/api/v1/users?page=2","path":"http://localhost/api/v1/users","per_page":20,"prev_page_url":null,"to":20,"total":30}}
     * @response 401 {"message": "Unauthenticated."}
     */
    public function listUsers(): JsonResponse
    {
        $users = User::select(['id as userId', 'email', 'firstName', 'lastName'])
            ->paginate(20);

        return $this->returnJson(true, $users->toArray());
    }
}
