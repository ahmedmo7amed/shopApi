@extends('layouts.app')

@section('title', 'Products')

@section('layouts.simple.css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/range-slider.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Products</h3>
@endsection
@php
    use Illuminate\Support\Str;
@endphp
@section('breadcrumb-items')
<li class="breadcrumb-item">Ecommerce</li>
<li class="breadcrumb-item active">Products</li>
@endsection
@section('content')
<div class="container-fluid product-wrapper">
    <div class="product-grid">
        <div class="feature-products">
            <div class="row">
                <div class="col-md-6 products-total">
                    <div class="square-product-setting d-inline-block">
                        <a class="icon-grid grid-layout-view" href="#" data-original-title="" title="">
                            <i data-feather="grid"></i>
                        </a>
                    </div>
                    <div class="square-product-setting d-inline-block">
                        <a class="icon-grid m-0 list-layout-view" href="#" data-original-title="" title="">
                            <i data-feather="list"></i>
                        </a>
                    </div>
                    <span class="d-none-productlist filter-toggle">
                        Filters<span class="ms-2"><i class="toggle-data" data-feather="chevron-down"></i></span>
                    </span>
                    <div class="grid-options d-inline-block">
                        <ul>
                            <li>
                                <a class="product-2-layout-view" href="#" data-original-title="" title="">
                                    <span class="line-grid line-grid-1 bg-primary"></span>
                                    <span class="line-grid line-grid-2 bg-primary"></span>
                                </a>
                            </li>
                            <li>
                                <a class="product-3-layout-view" href="#" data-original-title="" title="">
                                    <span class="line-grid line-grid-3 bg-primary"></span>
                                    <span class="line-grid line-grid-4 bg-primary"></span>
                                    <span class="line-grid line-grid-5 bg-primary"></span>
                                </a>
                            </li>
                            <li>
                                <a class="product-4-layout-view" href="#" data-original-title="" title="">
                                    <span class="line-grid line-grid-6 bg-primary"></span>
                                    <span class="line-grid line-grid-7 bg-primary"></span>
                                    <span class="line-grid line-grid-8 bg-primary"></span>
                                    <span class="line-grid line-grid-9 bg-primary"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 text-sm-end">
                    @auth
                        @if(auth()->user()->can('create', App\Models\Product::class))
                            <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
                                <i data-feather="plus"></i> Add New Product
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-md-3">
                    <div class="product-sidebar">
                        <div class="filter-section">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 f-w-600">Filters</h6>
                                </div>
                                <div class="card-body filter-cards-view">
                                    <div class="product-filter">
                                        <h6 class="f-w-600">Categories</h6>
                                        <div class="checkbox-animated mt-0">
                                            @foreach($categories ?? [] as $category)
                                                <label class="d-block" for="category-{{ $category->id }}">
                                                    <input type="checkbox"
                                                           id="category-{{ $category->id }}"
                                                           name="categories[]"
                                                           value="{{ $category->id }}"
                                                           class="category-filter">
                                                    {{ $category->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="product-filter">
                                        <h6 class="f-w-600">Price Range</h6>
                                        <input id="price-range" type="text" class="js-range-slider" name="price_range" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-md-9">
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-xl-4 col-sm-6 xl-4">
                                <div class="card">
                                    <div class="product-box">
                                        <div class="product-img ">
                                            @if($product->images)
                                                <div id="productCarousel{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach($product->images as $key => $image)
                                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                                <img class="img-fluid " src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <a class="carousel-control-prev" href="#productCarousel{{ $product->id }}" role="button" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#productCarousel{{ $product->id }}" role="button" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </a>
                                                </div>
                                            @else
                                                <img class="img-fluid" src="{{ asset('assets/images/product/default.png') }}" alt="Default Product Image">
                                            @endif
                                            <div class="product-hover">
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('products.show', $product) }}" class="btn" type="button">
                                                            <i data-feather="eye"></i>
                                                        </a>
                                                    </li>
                                                    @auth
                                                        @if(auth()->user()->can('update', $product))
                                                            <li>
                                                                <a href="{{ route('products.edit', $product) }}" class="btn" type="button">
                                                                    <i data-feather="edit"></i>
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(auth()->user()->can('delete', $product))
                                                            <li>
                                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn" onclick="return confirm('Are you sure you want to delete this product?')">
                                                                        <i data-feather="trash-2"></i>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endauth
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-details">
                                            <h4>{{ $product->name }}</h4>
                                            <p>{{ Str::limit($product->description, 100) }}</p>
                                            <div class="product-price">
                                                ${{ number_format($product->price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No products found.
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-12">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/range-slider/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('assets/js/range-slider/rangeslider-script.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>

<script>
    $(document).ready(function() {
        // Initialize price range slider
        $("#price-range").ionRangeSlider({
            type: "double",
            min: 0,
            max: 1000,
            from: 0,
            to: 1000,
            prefix: "$"
        });

        // Category filter functionality
        $('.category-filter').on('change', function() {
            let selectedCategories = [];
            $('.category-filter:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            // Update the products list based on selected categories
            // You can implement AJAX call here
        });
    });
</script>
@endsection
