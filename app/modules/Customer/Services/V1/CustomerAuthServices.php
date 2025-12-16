<?php

namespace Modules\Customer\Services\V1;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

class CustomerAuthServices
{
    public function registerCustomer($data)
    {
        $customer = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);

        event(new Registered($customer));
        Auth::login($customer);
        return $customer;
    }

    public function loginCustomer($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return ['user' => $user, 'token' => $token];
        }

        return null;
    }
    public function sendEmailVerification($user)
    {
        $user->notify(new VerifyEmail());
    }
    public function logoutCustomer($user)
    {
        $user->tokens()->delete();
    }
    public function resendVerificationEmail($user)
    {
        $user->sendEmailVerificationNotification();
    }
}
