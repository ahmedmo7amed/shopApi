<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cuba Admin Template</title>
    @yield('css')

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
  <body>
  @php
      use Illuminate\Support\Str;
  @endphp
    <div class="loader-wrapper">
      <div class="loader-index"><span></span></div>
      <svg>
        <defs></defs>
        <filter id="goo">
          <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
          <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"></fecolormatrix>
        </filter>
      </svg>
    </div>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->

      @include('layouts.app')

      <!-- Page Header Ends -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
{{--        @include('layouts.simple.sidebar')--}}
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          @include('layouts.simple.breadcrumb')
          <!-- Container-fluid starts-->
          @yield('content')
          <!-- Container-fluid Ends-->
        </div>

        <!-- footer start-->
        @include('layouts.simple.footer')
      </div>
    </div>
    @include('layouts.simple.script')
  </body>
</html>
