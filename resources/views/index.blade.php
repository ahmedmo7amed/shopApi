@extends('layouts.app')

@section('title', 'Home')

@section('layouts.simple.css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/range-slider.css') }}">
@endsection

@section('content')
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-industrial fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/images/logo.png') }}" alt="خزانات السلام" width="120">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">عن المصنع</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">المنتجات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">اتصل بنا</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="industrial-hero">
        <div class="hero-overlay">
            <div class="container text-center">
                <h1 class="display-4 fw-bold mb-4">خزانات السلام للمياه</h1>
                <p class="lead">جودة عالية - تصميم متطور - عمر افتراضي طويل</p>
                <div class="hero-cta mt-5">
                    <a href="#products" class="btn btn-danger btn-lg mx-2">تصفح المنتجات</a>
                    <a href="#contact" class="btn btn-outline-light btn-lg mx-2">طلب استشارة</a>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="industrial-about py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('assets/images/factory.jpg') }}" class="img-fluid rounded shadow" alt="عن المصنع">
                </div>
                <div class="col-md-6 py-4">
                    <h2 class="fw-bold mb-4">مصنع خزانات السلام</h2>
                    <p class="lead text-muted">
                        نقدم حلول تخزين المياه الأكثر أمانًا منذ عام 1998. نوفر خزانات المياه البلاستيكية بأنواعها المختلفة وفق أعلى معايير الجودة العالمية.
                    </p>
                    <div class="features-list mt-4">
                        <div class="feature-item">
                            <i class="fas fa-certificate"></i>
                            <span>ضمان 10 سنوات ضد التسريب</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-tools"></i>
                            <span>تصنيع وفق المقاييس السعودية</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>مواد خام مصرح بها من وزارة الصحة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="industrial-products py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">منتجاتنا</h2>

            @foreach($categories as $category)
                <div class="product-category mb-5">
                    <h3 class="category-title">{{ $category->name }}</h3>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($category->products as $product)
                            <div class="col">
                                <div class="card product-card h-100">
                                    <div class="product-badge">ضمان 10 سنوات</div>
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" class="card-img-top" alt="{{ $product->name }}">
                                    <div class="card-body">
                                        <h5 class="product-name">{{ $product->name }}</h5>
                                        <div class="product-specs">
                                            <div class="spec-item">
                                                <i class="fas fa-ruler-combined"></i>
                                                <span>السعة: {{ $product->capacity }} لتر</span>
                                            </div>
                                            <div class="spec-item">
                                                <i class="fas fa-arrows-alt-v"></i>
                                                <span>الارتفاع: {{ $product->height }} سم</span>
                                            </div>
                                            <div class="spec-item">
                                                <i class="fas fa-weight-hanging"></i>
                                                <span>الوزن: {{ $product->weight }} كجم</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="product-price">{{ number_format($product->price) }} ريال</span>
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-industrial">
                                                التفاصيل <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Certifications Section -->
    <section class="industrial-certifications bg-dark text-white py-5">
        <div class="container text-center">
            <h3 class="mb-5">شهادات الجودة والاعتماد</h3>
            <div class="row justify-content-center g-4">
                <div class="col-auto">
                    <img src="{{ asset('assets/images/cert1.png') }}" alt="شهادة الجودة" width="100">
                </div>
                <div class="col-auto">
                    <img src="{{ asset('assets/images/cert2.png') }}" alt="شهادة البيئة" width="100">
                </div>
                <div class="col-auto">
                    <img src="{{ asset('assets/images/cert3.png') }}" alt="شهادة الصحة" width="100">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="industrial-contact py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="contact-info bg-light p-4 rounded shadow">
                        <h4 class="mb-4">معلومات التواصل</h4>
                        <ul class="contact-list">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>الــدمام - الـفيصلية - شارع أبوبكر الصديق</span>
                                <span>الــخبر - الـثقبة - الشارع الرابع</span>

                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>+966554289000</span>
                            </li>
                            <li>
                                <i class="fab fa-whatsapp"></i>
                                <span>+966554289000</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>info@alsalamtanks.com</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Custom CSS -->
    <style>
        .bg-industrial {
            background-color: #2c3e50 !important;
        }

        .industrial-hero {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.8)),
            url('{{ asset("assets/images/hero-bg.jpg") }}');
            height: 80vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            margin-top: 76px;
        }

        .product-card {
            border: none;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.9rem;
        }

        .category-title {
            border-right: 5px solid #e74c3c;
            padding-right: 1rem;
            margin: 2rem 0;
        }

        .btn-industrial {
            background: #e74c3c;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
        }

        .btn-industrial:hover {
            background: #c0392b;
            color: white;
        }

        .spec-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .spec-item i {
            color: #e74c3c;
            margin-left: 0.5rem;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('assets/js/range-slider/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('assets/js/range-slider/rangeslider-script.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Customer Testimonials Carousel initialization
            $('#customerCarousel').carousel({
                interval: 5000
            });
        });
    </script>
@endsection
