<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'متجر إلكتروني')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
     <!-- Fontawesome css -->
     <link rel="stylesheet" type="text/css" href={{asset('assets/css/fontawesome.css')}}>
    <!-- ico-font -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/icofont.css')}}>
    <!-- Themify icon -->
    <link rel="stylesheet" type="text/css" href= {{asset('assets/css/vendors/themify.css')}}>
    <!-- Flag icon -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/flag-icon.css')}}>
    <!-- Feather icon -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/feather-icon.css')}}>
    <!-- Plugins css start -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/slick.css')}}>
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/slick-theme.css')}}>
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/scrollbar.css')}}>
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/select2.css')}}>
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/owlcarousel.css')}}>
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/range-slider.css')}}>
    <!-- Plugins css Ends -->
    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/vendors/bootstrap.css')}}>
    <!-- App css -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/style.css')}}>
    <link id="color" rel="stylesheet" href={{asset('assets/css/color-1.css')}} media="screen">
    <!-- Responsive css -->
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/responsive.css')}}>
    <link rel="stylesheet" type="text/css" href={{asset('assets/css/alsalam.css')}}>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/fontawesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=El+Messiri:wght@400..700&display=swap');
        .cairo-font {
                    font-family: "Cairo", serif;
                    font-optical-sizing: auto;
                    font-weight: 400;
                    font-style: normal;
                    font-variation-settings:
                        "slnt" 0;
                }
    </style>
</head>
<body class="font-light antialiased cairo-font">
    <nav class="navbar navbar-expand-lg navbar-light bg-light align-content-between">
        <a class="navbar-brand" href="/">
            <img src="http://localhost:8000/assets/images/logo/logo.png" alt="Logo"
                 class="img-fluid w-sm-200 w-md-150 w-lg-100" loading="lazy" style="max-width: 50px; height: auto;">
        </a>
        <div class="container px-4">


            <a class="navbar-brand" href="/">مصنع السلام للخزانات </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item" > Contact Us:<a style="color: #000; font-size: 15px; text-decoration: none;" href="tel:+966554289000"> +966554289000</a> | <E> <a style="color: #000; font-size: 15px; text-decoration: none;" href="mailto:info@alsalamtank.com">info@alsalamtank.com</a>  </E >
                     </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-industrial">
        <div class="container">
            </div>

        <div class="container">

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#main"
                aria-controls="main"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="main">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link p-2 p-lg-3 active" aria-current="page" href="/"> الرئيسية </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2 p-lg-3" href="{{route('products.index')}}">المنتجات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2 p-lg-3" href="#contact"> أتصل بنا </a>
                    </li>
                </ul>
{{--                <div class="search ps-3 pe-3 d-none d-lg-block">--}}
{{--                    <i class="fa-solid fa-magnifying-glass"></i>--}}
{{--                </div>--}}
{{--                <a class="btn rounded-pill main-btn" href="#"> تسجيل الدخول </a>--}}
            </div>
        </div>
    </nav>

{{--    <div class="container my-4">--}}
    <div>
        @yield('content')
    </div>
    @include('layouts.simple.footer')


    <!-- Add this to your layout file -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- feather icon js-->
    <script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
    <!-- scrollbar js-->
    <script src="{{asset('assets/js/scrollbar/simplebar.js')}}"></script>
    <script src="{{asset('assets/js/scrollbar/custom.js')}}"></script>
    <!-- Sidebar jquery-->
    <script src="{{asset('assets/js/config.js')}}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script id="menu" src="{{asset('assets/js/sidebar-menu.js')}}"></script>
    <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
    <script src="{{ asset('assets/js/header-slick.js') }}"></script>
    <script src="{{asset('assets/js/bootstrap/alsalam.js')}}"></script>
    @php
        use Illuminate\Support\Facades\Route;
    @endphp

    <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
    <script src="{{ asset('assets/js/header-slick.js') }}"></script>
    @yield('script')

    @if(Route::current()->getName() != 'popover')
        <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    @endif

    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{asset('assets/js/script.js')}}"></script>
    @if(Route::currentRouteName() == 'products.index')
        <script>
            new WOW().init();
        </script>
    @endif
</body>
</html>
