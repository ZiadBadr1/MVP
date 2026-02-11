<?php

namespace App\Helper;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function success($data =null,string $message = 'Success',int $code = 200): \Illuminate\Http\JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
                return response()->json([
                'success' => true,
                'message' => $message,
                'data' => UserResource::collection($data->items()),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ],
                'links' => [
                    'first' => $data->url(1),
                    'last' => $data->url($data->lastPage()),
                    'prev' => $data->previousPageUrl(),
                    'next' => $data->nextPageUrl(),
                ],
                'code' => $code
            ], $code);
        }
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