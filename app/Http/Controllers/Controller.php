<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function returnJson(
        bool $status,
        string|array $response = '',
        string|array $errors = '',
        int $code = 200
    ): \Illuminate\Http\JsonResponse {
        $result = ['status' => $status];

        if (!empty($response)) {
            $result['response'] = $response;
        }

        if (!empty($errors)) {
            $result['errors'] = $errors;
        }

        return response()->json($result, $code);
    }
}
