@props(['product'])
@php
    use Illuminate\Support\Str;
@endphp
<div class="card product-card">
    <div class="product-box">
        <div class="product-img position-relative">
            @if($product->discount_percentage > 0)
                <div class="ribbon ribbon-danger">{{ $product->discount_percentage }}% OFF</div>
            @endif

            @if($product->is_new)
                <div class="ribbon ribbon-success ribbon-right">NEW</div>
            @endif

            @if($product->images)
                <img class="img-fluid " style="height: 400px;" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
            @else
                <img class="img-fluid" src="{{ asset('assets/images/product/default.jpg') }}" alt="Default Product Image">
            @endif

            <div class="product-hover">
                <ul class="list-unstyled">
                    <li>
                        <a href="{{ route('products.show', $product) }}" class="btn" type="button" data-bs-toggle="tooltip" title="Quick View">
                            <i data-feather="eye"></i>
                        </a>
                    </li>
                    @auth
                        <li>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm" data-bs-toggle="tooltip" title="Add to Cart">
                                    <i data-feather="shopping-cart"></i>
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn" data-bs-toggle="tooltip"
                                        title="{{ $product->isInWishlist() ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                    <i data-feather="{{ $product->isInWishlist() ? 'heart' : 'heart' }}"
                                       class="{{ $product->isInWishlist() ? 'text-danger' : '' }}"></i>
                                </button>
                            </form>
                        </li>
                        @can('update', $product)
                            <li>
                                <a href="{{ route('products.edit', $product) }}" class="btn" data-bs-toggle="tooltip" title="Edit Product">
                                    <i data-feather="edit"></i>
                                </a>
                            </li>
                        @endcan
                    @endauth
                </ul>
            </div>
        </div>
        <div class="product-details p-3">
            <div class="rating mb-1">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star{{ $i <= $product->rating ? ' text-warning' : '-o text-muted' }}"></i>
                @endfor
                <span class="text-muted ms-2">({{ $product->reviews_count }} reviews)</span>
            </div>
            <h5 class="product-name mb-1">
                <a href="{{ route('products.show', $product) }}" class="text-dark">{{ $product->name }}</a>
            </h5>
            <p class="product-description text-muted mb-2">{{ Str::limit($product->description, 100) }}</p>
            <div class="product-price d-flex align-items-center gap-2">
                @if($product->discount_percentage > 0)
                    <h6 class="text-danger mb-0">${{ number_format($product->discounted_price, 2) }}</h6>
                    <small class="text-muted text-decoration-line-through">${{ number_format($product->original_price, 2) }}</small>
                @else
                    <h6 class="text-danger mb-0">${{ number_format($product->price, 2) }}</h6>
                @endif
            </div>
            <div class="addcart-btn">
                <a href="#" class="btn btn-primary ms-2 btn-sm" onclick="addToCart({{ $product->id }})">Add to Cart</a>
                <a class="btn btn-primary m-2 btn-sm" href="{{ route('product-page', $product) }}">View Details</a>
            </div>
            @if($product->stock_status === 'in_stock')
                <span class="badge bg-success mt-2" style="color: #FFFAFA">In Stock</span>
            @elseif($product->stock_status === 'low_stock')
                <span class="badge bg-warning mt-2">Low Stock</span>
            @else
                <span class="badge bg-danger mt-2">Out of Stock</span>
            @endif
        </div>
    </div>
</div>
