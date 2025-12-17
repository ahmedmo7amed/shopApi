<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'type'     => 'Customer',
        ]);

        $user->assignRole('Customer');

        event(new Registered($user));

        $token = JWTAuth::fromUser($user);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
