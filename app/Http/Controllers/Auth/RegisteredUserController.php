<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\APi\V1\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Services\Auth\JwtAuthService;

class RegisteredUserController extends Controller
{
    protected JwtAuthService $authService;
    public function __construct(JwtAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->string('password')),
        //     'type' => 'Customer'
        // ]);
        // $user->assignRole('Customer');
        // event(new Registered($user));
        // Auth::login($user);
        // return response()->noContent();
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $result = $this->authService->register($validated);

        return response([
            'success' => true,
            'message' => 'User registered successfully',
            'token'   => $result['token'],
            'type'    => 'Bearer',
            'user'    => $result['user'],
        ], 201);
    }
}
