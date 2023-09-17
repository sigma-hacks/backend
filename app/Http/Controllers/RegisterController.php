<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'pin' => 'integer',
            'identify' => 'integer',
            'employee_card' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['pin'] = bcrypt($data['pin']);

        // Set user role_id
        $data['role_id'] = User::ROLE_USER; // 1 - default role (user)
        $data['company_id'] = null;

        $data['code'] = MainHelper::cyr2lat($data['name']);

        $user = new User($data);

        try {
            $user->save();
        } catch (Exception $e) {
            return $this->sendServerError('User cant be saved', ['error' => $e->getMessage()]);
        }

        $success['token'] = $user->createToken('registration')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 200, ['User register successfully.']);
    }

    /**
     * User login
     */
    public function login(Request $request): JsonResponse
    {

        $login = (string) $request->input('login');
        $authType = (string) $request->input('type') ?? 'password';

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (preg_match('/^\d{3}-\d{3}$/', $login)) {
            $field = 'identify';
        } elseif (preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $login)) {
            $field = 'employee_card';
        } else {
            return $this->sendError('Invalid "login" format. Need to be: 123-456 or email, or card number (ex.: 2202-3512-3456-7890)', [], 412);
        }

        $isAttemptUser = Auth::attempt([
            $field => $login,
            'password' => $authType === 'pin' ? $request->input('pin') : $request->input('password'),
        ]);

        if ($isAttemptUser) {
            $user = Auth::user();
            $success['token'] = $user->createToken('login')->plainTextToken;

            return $this->sendResponse($success, 200, ['User login successfully.']);
        }

        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
    }
}
