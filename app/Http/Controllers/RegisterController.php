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
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'pin' => 'integer',
            'identify' => 'integer',
            'employee_card' => 'integer'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['pin'] = bcrypt($data['pin']);

        // Set user role_id
        $data['role_id'] = 1; // 1 - default role (user)
        $data['company_id'] = null;

        $data['code'] = MainHelper::cyr2lat($data['name']);

        $user = new User($data);

        try {
            $user->save();
        } catch (Exception $e) {
            return $this->sendServerError('User cant be saved', ['error' => $e->getMessage()]);
        }

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 200, ['User register successfully.']);
    }

    /**
     * User login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $isAttemptUser = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if( $isAttemptUser ) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;

            return $this->sendResponse($success, 200, ['User login successfully.']);
        }

        return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
    }

}
