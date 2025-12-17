<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle login request.
     */
    public function store(LoginRequest $request): Response
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {


                return response([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
                //create role for user
                // auth()->user()->assignRole('Customer');

            return response([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        return response([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    /**
     * Refresh JWT token.
     */
    public function refresh(): Response
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response([
                'success' => true,
                'message' => 'Token refreshed',
                'token' => $newToken,
                'type' => 'bearer',
                'user' => auth()->user(),
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 200);
        } catch (JWTException $e) {
            return response([
                'success' => false,
                'message' => 'Could not refresh token'
            ], 500);
        }
    }

    /**
     * Logout user (invalidate token).
     */
    public function destroy(): Response
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);
        } catch (JWTException $e) {
            return response([
                'success' => false,
                'message' => 'Could not invalidate token'
            ], 500);
        }
    }
}
