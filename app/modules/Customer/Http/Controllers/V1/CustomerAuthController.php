<?php

namespace Modules\Customer\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Modules\Customer\Services\V1\CustomerAuthServices;
use Pest\ArchPresets\Custom;

class CustomerAuthController extends Controller
{
    protected $customerAuthServices;
    public function __construct(CustomerAuthServices $customerAuthServices)
    {
        $this->customerAuthServices = $customerAuthServices;
    }

    public function showLoginForm()
    {
        return view('authentication.login');
    }

    public function login(Request $request)
    {
        // $credentials = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);
        // if (Auth::attempt(array_merge($credentials, ['type' => 'user']))) {
        //     return redirect()->intended('/');
        // }
        // return redirect()->back()->with('error', ' بيانات التسجيل غير صحيحة');
        $credentials = $request->only('email', 'password');
        $loginResult = $this->customerAuthServices
        ->loginCustomer(array_merge($credentials, ['type' => 'customer']));
        if ($loginResult) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'user' => $loginResult['user'],
                'token' => $loginResult['token'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }
    }

    // public function showRegisterForm()
    // {
    //     return view('authentication.register');
    // }
//    public function register(Request $request)
//    {
//        $request->validate([
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
//            'password' => ['required', 'confirmed', 'min:8', Password::defaults()],
//        ]);
//        $user = User::create([
//            'name' => $request->name,
//            'email' => $request->email,
//            'password' => Hash::make($request->password),
//            'type' => 'customer'
//        ]);
//        $user->assigneRole('customer');
//        Auth::login($user);
//        return redirect()->route('login');
//    }

    public function logout(Request $request)
    {
        // Auth::logout();
        // $request->session()->invalidate(); // إبطال الجلسة
        // $request->session()->regenerateToken(); // إعادة إنشاء توكن الحماية
        // return redirect('/'); // إعادة توجيه المستخدم إلى الصفحة الرئيسية
        $user = Auth::user();
        $this->customerAuthServices->logoutCustomer($user);
        return response()->json([
            'success' => true,
            'message' => 'Logout successful!',200
        ]);
    }

    public function register(RegisterRequest $request)
    {
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'type' => 'user'
        // ]);
        // $user->assignRole('Customer');
        // event(new Registered($user));
        // Auth::login($user);
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Registration successful!',
        //     'redirect' => route('login'), // تأكد أن هذا المسار موجود
        // ]);
        //return redirect()->route('verification.notice')->with('success', ' Your account has been created successfully please verify your email address');
        $this->customerAuthServices
        ->registerCustomer($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            // 'redirect' => route('login'), // تأكد أن هذا المسار موجود
        ],201);
    }

    // public function verify()
    // {
    //     return view('authentication.verify');
    // }

    public function resendVerification(Request $request)
    {

        // $user = Auth::user();
        // // Determine verification status: prefer hasVerifiedEmail() when available, otherwise check email_verified_at
        // $isVerified = null;
        // if (is_object($user) && method_exists($user, 'hasVerifiedEmail')) {
        //     $isVerified = $user->hasVerifiedEmail();
        // } else {
        //     $isVerified = !empty($user->email_verified_at);
        // }
        // if ($isVerified) {
        //     return redirect()->route('home')->with('success', 'Your email is already verified');
        // }
        // // Prefer built-in method when available, otherwise send notification as a fallback
        // if (is_object($user) && method_exists($user, 'sendEmailVerificationNotification')) {
        //     $user->sendEmailVerificationNotification();
        // } elseif (is_object($user) && method_exists($user, 'notify')) {
        //     $user->notify(new VerifyEmail());
        // } else {
        //     // Last resort: send directly via Notification facade to the user's email
        //     Notification::route('mail', $user->email)->notify(new VerifyEmail());
        // }
        // return redirect()->back()->with('success', 'Verification link sent successfully');
        $user = Auth::user();
        if($user->hasVerifiedEmail()){
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified',200
            ]);
        }
        $this->customerAuthServices->resendVerificationEmail($user);
        return response()->json([
            'success' => true,
            'message' => 'Verification link sent successfully',200
        ]);
    }
}
