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
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        // Set user role_id
        $input['role_id'] = 1; // 1 - default role (user)
        $input['company_id'] = null;

        $input['code'] = MainHelper::cyr2lat($input['name']);

        $user = new User($input);

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
    public function login(Request $request)
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
