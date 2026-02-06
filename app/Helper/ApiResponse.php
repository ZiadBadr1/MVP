<?php

namespace App\Helper;

class ApiResponse
{
    public static function success($data =null,string $message = 'Success',int $code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ];
        return response()->json($response, $code);
    }
    public static function error($message = 'Error',int $code = 400,$errors = null): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code
        ];
        return response()->json($response, $code);
    }
}