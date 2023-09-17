<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param  array  $messages
     */
    public function sendResponse($result, int $code = 200, array|string $messages = []): JsonResponse
    {
        $response = [
            'status' => true,
            'data' => $result,
        ];

        if ($messages) {
            $response['message'] = $messages;
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @param  array  $messages
     */
    public function sendError(string $error, array|string $messages = [], int $code = 404): JsonResponse
    {
        $response = [
            'status' => false,
            'error' => $error,
        ];

        if (! empty($messages)) {
            $response['messages'] = $messages;
        }

        return response()->json($response, $code);
    }

    public function sendServerError(string $error, array|string $messages = []): JsonResponse
    {
        return $this->sendError($error, $messages, 500);
    }
}
