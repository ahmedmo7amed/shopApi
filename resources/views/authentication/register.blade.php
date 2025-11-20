@if(Illuminate\Support\Facades\Auth::user() && Illuminate\Support\Facades\Auth::user()->hasVerifiedEmail())

    <script>window.location.href = "{{ route('home') }}";</script>

@endif
@if(Illuminate\Support\Facades\Auth::user())
    <script>window.location.href = "{{ route('verification.notice') }}";</script>
@endif
@extends('layouts.authentication.master')
@section('title', 'Sign-up')

@section('css')
<!-- إضافة أي ملفات CSS إضافية هنا -->
@endsection

@section('style')
<!-- إضافة أي أنماط CSS مخصصة هنا -->
@endsection

@section('content')
<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div id="card" class="login-card">
            <div>
               <div>
                  <a class="logo" href="{{ route('index') }}">
                     <img class="img-fluid for-light" src="{{ asset('assets/images/logo/login.png') }}" alt="login page">
                     <img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt="login page">
                  </a>
               </div>
               <div class="login-main">
                  <form class="theme-form" action="{{ route('register') }}" method="POST" id="registrationForm">
                     @csrf <!-- إضافة CSRF Token -->

                     <div id="message"></div> <!-- لعرض الرسائل الناتجة عن JavaScript -->

                     <h4>Create your account</h4>
                     <p>Enter your personal details to create an account</p>

                     <!-- حقل الاسم الأول -->
                     <div class="form-group">
                        <label class="col-form-label pt-0">Your Name</label>
                        <div class="row g-2">
                           <div class="col-6">
                              <input class="form-control" type="text" placeholder="First name" name="name" required>
                           </div>
                           <div class="col-6">
                              <input class="form-control" type="text" placeholder="Last name" name="last_name" required>
                           </div>
                        </div>
                     </div>

                     <!-- حقل البريد الإلكتروني -->
                     <div class="form-group">
                        <label class="col-form-label">Email Address</label>
                        <input class="form-control" type="email" placeholder="Test@gmail.com" name="email" required>
                     </div>

                     <!-- حقل كلمة المرور -->
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="*********" required>
                        <div class="show-hide"><span class="show"></span></div>
                     </div>

                     <!-- حقل تأكيد كلمة المرور -->
                     <div class="form-group">
                        <label class="col-form-label">Confirm Password</label>
                        <input class="form-control" type="password" name="password_confirmation" placeholder="*********" required>
                        <div class="show-hide"><span class="show"></span></div>
                     </div>

                     <!-- عرض أخطاء التحقق من الصحة -->
                     @if ($errors->any())
                        <div class="alert alert-danger">
                           <ul>
                              @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                              @endforeach
                           </ul>
                        </div>
                     @endif

                     <!-- سياسة الخصوصية -->
                     <div class="form-group mb-0">
                        <div class="checkbox p-0">
                           <input id="checkbox1" type="checkbox" required>
                           <label class="text-muted" for="checkbox1">Agree with <a href="#">Privacy Policy</a></label>
                        </div>
                        <button class="btn btn-primary btn-block" id="btnRegister">Create Account</button>
                     </div>

                     <!-- عرض رسائل الجلسة (مثل الأخطاء أو النجاح) -->
                     @if(session('error'))
                        <div class="alert alert-danger">
                           {{ session('error') }}
                        </div>
                     @endif

                     @if(session('success'))
                        <div class="alert alert-success">
                           {{ session('success') }}
                        </div>
                     @endif

                     <!-- خيارات التسجيل عبر وسائل التواصل الاجتماعي -->
                     <h6 class="text-muted mt-4 or">Or signup with</h6>
                     <div class="social mt-4">
                        <div class="btn-showcase">
                           <a class="btn btn-light" href="https://www.linkedin.com/login" target="_blank">
                              <i class="txt-linkedin" data-feather="linkedin"></i> LinkedIn
                           </a>
                           <a class="btn btn-light" href="https://twitter.com/login?lang=en" target="_blank">
                              <i class="txt-twitter" data-feather="twitter"></i> Twitter
                           </a>
                           <a class="btn btn-light" href="https://www.facebook.com/" target="_blank">
                              <i class="txt-fb" data-feather="facebook"></i> Facebook
                           </a>
                        </div>
                     </div>

                     <!-- رابط تسجيل الدخول -->
                     <p class="mt-4 mb-0">Already have an account? <a class="ms-2" href="{{ route('login') }}">Sign in</a></p>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById('registrationForm');
      const message = document.getElementById('message');
      const registerButton = document.getElementById('btnRegister');

      if (form && registerButton) {
         form.addEventListener('submit', async function(event) {
            event.preventDefault(); // منع الإرسال الافتراضي للنموذج

            message.innerHTML = ''; // مسح الرسائل القديمة

            const formData = new FormData(form);
            registerButton.disabled = true;
            registerButton.innerHTML = 'Please wait...';

            try {
               const response = await fetch('{{ route('register') }}', {
                  method: 'POST',
                  headers: {
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Accept': 'application/json'
                  },
                  body: formData
               });

               const data = await response.json();

               if (data.success) {
                  window.location.href = data.redirect || '{{ route('login') }}';
               } else {
                  if (data.errors) {
                     let errors = '';
                     for (const key in data.errors) {
                        errors += `<div class="alert alert-danger">${data.errors[key][0]}</div>`;
                     }
                     message.innerHTML = errors;
                  } else {
                     message.innerHTML = `<div class="alert alert-danger">${data.message || 'Something went wrong. Please try again.'}</div>`;
                  }
               }
            } catch (error) {
               console.error('Error:', error);
               message.innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
            } finally {
               registerButton.disabled = false;
               registerButton.innerHTML = 'Create Account';
            }
         });
      }
   });
</script>
@endsection