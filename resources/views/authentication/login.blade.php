@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo" href="{{ route('index') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/login.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  <form class="theme-form" action="{{route('login')}}" method="POST">
                      @csrf
                     <h4>Sign in to account</h4>
                     <p>Enter your email & password to login</p>

                      <!-- عرض الأخطاء هنا -->
                      @if(session('error'))
                          <div class="alert alert-danger">
                              {{ session('error') }}
                          </div>
                      @endif

                      <!-- عرض أخطاء التحقق من المدخلات -->
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif

                     <div class="form-group">
                        <label class="col-form-label">Email Address</label>
                        <input class="form-control" type="email" required="" placeholder="Test@gmail.com">

                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="login[password]" required="" placeholder="*********">
                        <div class="show-hide"><span class="show">                         </span></div>

                     </div>
                     <div class="form-group mb-0">
                        <div class="checkbox p-0">
                           <input id="checkbox1" type="checkbox">
                           <label class="text-muted" for="checkbox1">Remember password</label>
                        </div>
                        <a class="link" href="{{ route('forget-password') }}">Forgot password?</a>
                        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                     </div>
                     <h6 class="text-muted mt-4 or">Or Sign in with</h6>
                     <div class="social mt-4">
                        <div class="btn-showcase"><a class="btn btn-light" href="https://www.linkedin.com/login" target="_blank"><i class="txt-linkedin" data-feather="linkedin"></i> LinkedIn </a><a class="btn btn-light" href="https://twitter.com/login?lang=en" target="_blank"><i class="txt-twitter" data-feather="twitter"></i>twitter</a><a class="btn btn-light" href="https://www.facebook.com/" target="_blank"><i class="txt-fb" data-feather="facebook"></i>facebook</a></div>
                     </div>
                     <p class="mt-4 mb-0">Don't have account?<a class="ms-2" href="{{  route('register') }}">Create Account</a></p>
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
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                var formData = new FormData(this); // Get the form data

                $.ajax({
                    url: '{{ route("login") }}', // Your route for login
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '/'; // Redirect to home or any page on successful login
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors; // Get the validation errors

                        // Clear previous error messages
                        $('#error-messages').html('');

                        // Show new error messages
                        $.each(errors, function(field, messages) {
                            messages.forEach(function(message) {
                                $('#error-messages').append('<div class="alert alert-danger">' + message + '</div>');
                            });
                        });
                    }
                });
            });
        });
    </script>

@endsection
