<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class AuthController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');

        $this->middleware('guest')->except('logout');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $userData = $request->only('username', 'password', 'password_confirmation');

        try {
            $this->validateRegister($userData);
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), 422, $e->errors());
        }

        $user = $this->registerUser($userData);

        $access_token = $user->createToken('authToken')->plainTextToken;

        return $this->sendResponse(compact('access_token'), 'Register successful.', 201);
    }

    /**
     * @param  array  $input
     * @return mixed
     * @throws ValidationException
     */
    private function validateRegister(array $input): array
    {
        return Validator::make($input, [
            'username' => 'required|max:55|unique:users',
            'password' => 'required|confirmed'
        ])->validate();
    }

    /**
     * @param  array  $userData
     * @return User|Model
     */
    private function registerUser(array $userData) : User
    {
        $userData['password'] = bcrypt($userData['password']);

        return User::create($userData);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        try {
            $this->validateLogin($credentials);
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), 422, $e->errors());
        }

        if (!auth()->attempt($credentials)) {
            return $this->sendError(trans('auth.failed'), 422);
        }

        /** @var User $user */
        $user = auth()->user();

        $access_token = $user->createToken('authToken')->plainTextToken;

        return $this->sendResponse(compact('access_token'), 'Login successful.');
    }

    /**
     * @param  array  $input
     * @return array
     * @throws ValidationException
     */
    private function validateLogin(array $input): array
    {
        return Validator::make($input, [
            'username' => 'required|max:55',
            'password' => 'required'
        ])->validate();
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        return $this->sendSuccess('Logout successful.');
    }
}
