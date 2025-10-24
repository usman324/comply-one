<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

trait RespondsWithJson
{
    public static function getDefaultResponse(
        $response,
    ): \Illuminate\Http\JsonResponse {
        $statusCode = $response->status();
        return response()->json(
            json_decode($response->content()),
            $statusCode
        );
    }

    /**
     * Normalized error response to be used by all API controllers.
     *
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function error(string $message, $data = [], int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
        ], $status);
    }

    /**
     * Normalized success response to be used by all API controllers.
     *
     * @param string $message
     * @param array|Collection $data
     * @param int $status
     * @return JsonResponse
     */
    protected function success(string $message, $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
