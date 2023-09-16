<?php

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;

abstract class AbstractMiddleware
{
    public function forbidden(): JsonResponse
    {
        return response()->json([
            'status' => false,
            'error' => 'Permission denied',
        ], 403);
    }
}
