<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Response;
use App\Models\User;
use Validator;

class AuthController extends AppBaseController
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $input = $request->all();

        try {
            $this->validateRegister($input);
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), 422, $e->errors());
        }

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $access_token = $user->createToken('authToken')->plainTextToken;

        return $this->sendResponse(compact('access_token'), 'Register successful.', 201);
    }

    /**
     * @param  array  $input
     * @return mixed
     * @throws ValidationException
     */
    private function validateRegister(array $input)
    {
        return Validator::make($input, [
            'username' => 'required|max:55|unique:users',
            'password' => 'required|confirmed'
        ])->validate();
    }
}
