<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{

    public function showLoginForm()
    {
        return view('authentication.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['type' => 'Customer']))) {
            return redirect()->intended('/');

        }
        return redirect()->back()->with('error', 'يانات التسجيل غير صحيحة');
    }

    public function showRegisterForm()
    {
        return view('authentication.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:8', Password::defaults()],

        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'customer'
        ]);
        $user->assigneRole('customer');
        Auth::login($user);
        return redirect()->route('login');
    }

        public function logout()
        {
            Auth::logout();
            return redirect()->route('home');
        }
}
