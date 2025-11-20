
@if(!Illuminate\Support\Facades\Auth::user())
    <script>window.location.href = "{{ route('login') }}";</script>
@endif
@if(Illuminate\Support\Facades\Auth::user() && Illuminate\Support\Facades\Auth::user()->hasVerifiedEmail())

    <script>window.location.href = "{{ route('home') }}";</script>
@endif
@extends('layouts.app')
{{--@extends('layouts.authentication.master')--}}
@section('title', 'Verify')
@section('content')

<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo" href="{{ route('index') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/login.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  @include('layouts.flash_messages')
                  <form class="theme-form" action="{{route('verification.send')}}" method="POST">
                     @csrf
                     <h4>Verify Your Email Address</h4>
                     <p>Before proceeding, please check your email for a verification link. If you did not receive the email, <a href="{{ route('verification.send') }}">click here to request another</a>.</p>
                     <div class="form-group mb-0">
                        <button class="btn btn-primary btn-block" type="submit">Resend Verification Email</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
