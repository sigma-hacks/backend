<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorizationController extends BaseController
{

    /**
     * Create personal token for auth requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPersonalToken(Request $request): JsonResponse
    {
        $token = $request->user()?->createToken($request->token_name);

        return $this->sendResponse([
            'token' => $token?->plainTextToken
        ]);
    }

}
