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

        if( !$request->has('token_name') ) {
            return $this->sendError('Field "token_name" is required and can\'t be empty', '', 412);
        }

        $token = $request->user()?->createToken($request->input('token_name'));

        return $this->sendResponse([
            'token' => $token?->plainTextToken
        ]);
    }

}
