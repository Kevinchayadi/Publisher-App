<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use JsonResponseTrait; // Trait for standardized JSON responses

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->badRequest('Validation failed', [
                'errors' => $validator->messages()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $this->success(null, 'User created successfully', 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->badRequest('Validation failed', [
                'errors' => $validator->messages()
            ]);
        }

        $credentials = $request->only(['email', 'password']);
        try {
            if (!($token = JWTAuth::attempt($credentials))) {
                return $this->error('Invalid credentials', 'Invalid credentials provided', 401);
            }
        } catch (\Throwable $th) {
            return $this->serverError('Could not create token');
        }

        return $this->success(compact('token'), 'Login successful');
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!($user = JWTAuth::parseToken()->authenticate())) {
                return $this->error('User not found', 'User not found in the system', 404);
            }
        } catch (JWTException $e) {
            return $this->serverError('Could not find user');
        }

        return $this->success(compact('user'), 'User retrieved successfully');
    }
}
