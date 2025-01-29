@extends('layouts.simple.master')

@section('title', $product->name)

@section('layouts.simple.css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/photoswipe.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">

@endsection
@php
    use Illuminate\Support\Str;
@endphp
@section('breadcrumb-title')
<h3>{{ $product->name }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Ecommerce</li>
<li class="breadcrumb-item">
    <a href="{{ route('products.index') }}">Products</a>
</li>
<li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row product-page-main">
                <!-- Product Images -->
                <div class="col-xl-5 col-md-6">
                    <div class="product-slider owl-carousel owl-theme" id="sync1">
                        @if($product->images)
                            @foreach($product->images as $image)
                                <div class="item">
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid">
                                </div>
                            @endforeach
                        @else
                            <div class="item">
                                <img src="{{ asset('assets/images/product/default.jpg') }}"
                                     alt="Default Product Image"
                                     class="img-fluid">
                            </div>
                        @endif
                    </div>
                    @if($product->images)
                        <div class="owl-carousel owl-theme" id="sync2">
                            @foreach($product->images as $image)
                                <div class="item">
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="col-xl-7 col-md-6">
                    <div class="product-page-details">
                        <h3>{{ $product->name }}</h3>
                        <div class="product-price mb-3">
                            @if($product->discount_percentage > 0)
                                <h4 class="text-danger d-inline">${{ number_format($product->discounted_price, 2) }}</h4>
                                <del class="text-muted ms-2">${{ number_format($product->original_price, 2) }}</del>
                                <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                            @else
                                <h4 class="text-danger">${{ number_format($product->price, 2) }}</h4>
                            @endif
                        </div>

                        <!-- Rating -->
                        <div class="product-rating mb-3">
                            <div class="d-flex align-items-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star{{ $i <= $product->rating ? ' text-warning' : '-o text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">{{ number_format($product->rating, 1) }} out of 5</span>
                            </div>
                            <p class="text-muted">{{ $product->reviews_count }} customer reviews</p>
                        </div>

                        <!-- Stock Status -->
                        <div class="stock-status mb-3">
                            @if($product->stock_status === 'in_stock')
                                <span class="badge bg-success">In Stock</span>
                                <span class="text-muted ms-2">{{ $product->stock_quantity }} units available</span>
                            @elseif($product->stock_status === 'low_stock')
                                <span class="badge bg-warning">Low Stock</span>
                                <span class="text-muted ms-2">Only {{ $product->stock_quantity }} units left</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="product-description mb-4">
                            <h6 class="mb-2">Description</h6>
                            <p>{{ $product->description }}</p>
                        </div>

                        <!-- Features -->
                        @if($product->features)
                            <div class="product-features mb-4">
                                <h6 class="mb-2">Key Features</h6>
                                <ul class="list-unstyled">
                                    @foreach($product->features as $feature)
                                        <li class="mb-1">
                                            <i class="fa fa-check text-success me-2"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Add to Cart Form -->
                        @if($product->stock_status !== 'out_of_stock')
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto common-flex">
{{--                                        <div class="btn-group dropup">--}}

{{--                                            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropup</button>--}}
{{--                                            <ul class="dropdown-menu">--}}
{{--                                                <li><a class="dropdown-item" href="#!">Menu item</a></li>--}}
{{--                                                <li><a class="dropdown-item" href="#!">Menu item</a></li>--}}
{{--                                                <li><a class="dropdown-item" href="#!">Menu item</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}




                                    </div>
                                    <div class="col-auto">
                                        <label for="quantity" class="col-form-label">Quantity</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="number"
                                               id="quantity"
                                               name="quantity"
                                               class="form-control"
                                               value="1"
                                               min="1"
                                               max="{{ $product->stock_quantity }}">

                                    </div>

{{--                                    <h2>الخيارات:</h2>--}}
{{--                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">--}}
{{--                                        @csrf--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="capacity">اختار السعة:</label>--}}
{{--                                            <select name="capacity" id="capacity" class="form-control">--}}
{{--                                                @foreach($product->options as $option)--}}
{{--                                                    <optgroup label="{{ $option->name }}">--}}
{{--                                                        @foreach($option->values as $value)--}}
{{--                                                            <option value="{{ $value->id }}"--}}
{{--                                                                    data-length="{{ $value->length }}"--}}
{{--                                                                    data-diameter="{{ $value->diameter }}"--}}
{{--                                                                    data-height="{{ $value->height }}">--}}
{{--                                                                {{ $value->value }} - الطول: {{ $value->length }} سم - القطر: {{ $value->diameter }} سم - الارتفاع: {{ $value->height }} سم--}}
{{--                                                            </option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </optgroup>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

{{--                                        <button type="submit" class="btn btn-primary mt-3">إضافة إلى السلة</button>--}}
{{--                                    </form>--}}

                                    <!-- Wishlist Toggle -->
                                    <div class="col-auto">
                                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="d-inline">
                                            @csrf

                                            <button type="submit" class="btn btn-outline-danger">

{{--                                                //comment wishlist toggle--}}
                                              <i data-feather="{{ $product->isInWishlist() ? 'heart' : 'heart' }}"
                                                  class="{{ $product->isInWishlist() ? 'text-danger' : '' }}"></i>
                                                {{ $product->isInWishlist() ? 'Remove from Wishlist' : 'Add to Wishlist' }}

                                            </button>

                                                <button type="submit" class="btn btn-primary">
                                                    <i data-feather="shopping-cart" class="me-1"></i>
                                                    Add to Cart
                                                </button>

                                        </form>


                                    </div>
                                </div>
                            </form>
                        @endif

                        <!-- Categories and Tags -->
                        <div class="product-tags">
                            <div class="mb-2">
                                <strong>Category:</strong>
                                <a href="{{ route('products.by.category', $product->category) }}" class="ms-2">

                                    {{ $product->category->name }}
                                </a>
                            </div>
                            @if(optional($product->tags)->count() > 0)
                                <div>
                                    <strong>Tags:</strong>
                                    @foreach($product->tags as $tag)
                                        <a href="{{ route('products.by.tag', $tag) }}" class="badge bg-light text-dark ms-1">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Reviews -->
    <div class="card">
        <div class="card-header">
            <h5>Customer Reviews</h5>
        </div>
        <div class="card-body">
            @if(optional($product->reviews)->isEmpty())
                <p class="text-muted text-center">No reviews yet. Be the first to review this product!</p>
            @else
                <div class="reviews-list">
                    @foreach($product->reviews as $review)
                        <div class="review-item mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star{{ $i <= $review->rating ? ' text-warning' : '-o text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination for reviews if needed -->
                @if($product->reviews->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $product->reviews->links() }}
                    </div>
                @endif
            @endif

            <!-- Add Review Form -->
            @auth
                 @if(auth()->check() && auth()->user()->hasPurchased($product))
                    <div class="add-review mt-4">
                        <h6>Add Your Review</h6>
                        <form action="{{ route('products.reviews.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio"
                                               id="star{{ $i }}"
                                               name="rating"
                                               value="{{ $i }}"
                                               class="d-none"
                                               required>
                                        <label for="star{{ $i }}" class="me-2">
                                            <i class="fa fa-star-o"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Your Review</label>
                                <textarea class="form-control"
                                          id="comment"
                                          name="comment"
                                          rows="3"
                                          required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-center mt-4">
                    <p>Please <a href="{{ route('login') }}">login</a> to leave a review.</p>
                </div>
            @endauth
        </div>
    </div>

    <!-- Related Products -->
    @if(optional( $relatedProducts)->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5>Related Products</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <x-product-card :product="$relatedProduct" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
<script src="{{ asset('assets/js/photoswipe/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/js/photoswipe/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/js/photoswipe/photoswipe.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize main product slider
    var sync1 = $("#sync1");
    var sync2 = $("#sync2");
    var slidesPerPage = 4;
    var syncedSecondary = true;

    sync1.owlCarousel({
        items: 1,
        slideSpeed: 2000,
        nav: true,
        autoplay: false,
        dots: false,
        loop: true,
        responsiveRefreshRate: 200,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    }).on('changed.owl.carousel', syncPosition);

    sync2.on('initialized.owl.carousel', function() {
        sync2.find(".owl-item").eq(0).addClass("current");
    }).owlCarousel({
        items: slidesPerPage,
        dots: false,
        nav: false,
        smartSpeed: 200,
        slideSpeed: 500,
        slideBy: slidesPerPage,
        responsiveRefreshRate: 100
    }).on('changed.owl.carousel', syncPosition2);

    function syncPosition(el) {
        var count = el.item.count - 1;
        var current = Math.round(el.item.index - (el.item.count / 2) - .5);
        if (current < 0) {
            current = count;
        }
        if (current > count) {
            current = 0;
        }
        sync2.find(".owl-item").removeClass("current").eq(current).addClass("current");
        var onscreen = sync2.find('.owl-item.active').length - 1;
        var start = sync2.find('.owl-item.active').first().index();
        var end = sync2.find('.owl-item.active').last().index();
        if (current > end) {
            sync2.data('owl.carousel').to(current, 100, true);
        }
        if (current < start) {
            sync2.data('owl.carousel').to(Math.max(current - (onscreen), 0), 100, true);
        }
    }

    function syncPosition2(el) {
        if (syncedSecondary) {
            var number = el.item.index;
            sync1.data('owl.carousel').to(number, 100, true);
        }
    }

    sync2.on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = $(this).index();
        sync1.data('owl.carousel').to(number, 300, true);
    });

    // Rating Input Handling
    $('.rating-input label').hover(
        function() {
            $(this).prevAll('label').addBack().find('i').removeClass('fa-star-o').addClass('fa-star text-warning');
            $(this).nextAll('label').find('i').removeClass('fa-star text-warning').addClass('fa-star-o');
        },
        function() {
            var selectedRating = $('input[name="rating"]:checked').val();
            if (!selectedRating) {
                $('.rating-input label i').removeClass('fa-star text-warning').addClass('fa-star-o');
            } else {
                $('.rating-input label').each(function(index) {
                    if (5 - index <= selectedRating) {
                        $(this).find('i').removeClass('fa-star-o').addClass('fa-star text-warning');
                    } else {
                        $(this).find('i').removeClass('fa-star text-warning').addClass('fa-star-o');
                    }
                });
            }
        }
    ).click(function() {
        $(this).prevAll('label').addBack().find('i').removeClass('fa-star-o').addClass('fa-star text-warning');
        $(this).nextAll('label').find('i').removeClass('fa-star text-warning').addClass('fa-star-o');
    });
});


    document.getElementById('capacity').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];

    // استخراج التفاصيل من البيانات المخزنة في الـ data attributes
    var length = selectedOption.getAttribute('data-length');
    var diameter = selectedOption.getAttribute('data-diameter');
    var height = selectedOption.getAttribute('data-height');

    // تحديث عرض التفاصيل
    document.getElementById('dimensions').innerHTML = `
            <strong>الأبعاد:</strong><br>
            الطول: ${length} سم<br>
            القطر: ${diameter} سم<br>
            الارتفاع: ${height} سم
        `;
});


</script>
@endsection
