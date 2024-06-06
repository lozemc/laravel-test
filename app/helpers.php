<?php

use Illuminate\Http\JsonResponse;

if (!function_exists("returnJson")) {
    function returnJson(
        bool $status,
        string|array $response = '',
        string|array $errors = '',
        int $code = 200
    ): JsonResponse {
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
