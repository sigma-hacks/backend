<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    /**
     * Update user data
     */
    public function update(Request $request, int $id): JsonResponse
    {

        if (! MainHelper::isAdmin()) {
            if (MainHelper::getUserId() !== $id) {
                return $this->sendError('Permissions denied', ['Your ID not equals with '.$id], 403);
            }
        }

        $user = User::where('id', $id)->first();

        if ($request->has('identify')) {
            $user->identify = (int) $request->input('identify');
        }

        if ($request->has('employee_card')) {
            $user->employee_card = (int) $request->input('employee_card');
        }

        if ($request->has('photo')) {
            $user->photo = (string) $request->input('photo');
        }

        if ($request->has('name')) {
            $user->name = (string) $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = (string) $request->input('email');
        }

        if ($request->has('code')) {
            $user->code = (string) $request->input('code');
        }

        if ($request->has('pin')) {
            $user->pin = bcrypt($request->input('pin'));
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        if (MainHelper::isAdmin() && $user->id !== MainHelper::getUserId()) {
            if ($request->has('role_id')) {
                $user->role_id = (int) $request->input('role_id');
            }

            if ($request->has('company_id')) {
                $user->company_id = (int) $request->input('company_id');
            }
        }

        return $this->sendResponse([]);
    }

    /**
     * Getting user data
     */
    public function me(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user());
    }
}
