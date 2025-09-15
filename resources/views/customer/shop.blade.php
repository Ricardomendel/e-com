@extends('layouts.dashboard')

@section('title', 'Shop Products')
@section('dashboard-type', 'Customer')
@section('page-title', 'Shop Products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Shop</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('customer.shop') }}">
            <i class="fas fa-shopping-bag me-2"></i> Shop
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.cart') }}">
            <i class="fas fa-shopping-cart me-2"></i> Cart
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.orders') }}">
            <i class="fas fa-list me-2"></i> My Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.profile') }}">
            <i class="fas fa-user me-2"></i> Profile
        </a>
    </li>
@endsection

@section('header-actions')
    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
    <a href="{{ route('customer.cart') }}" class="btn btn-primary">
        <i class="fas fa-shopping-cart me-1"></i> Cart (<span id="cart-count">0</span>)
    </a>
@endsection

@section('content')
<!-- Search and Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search products..." id="search-input" name="search" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="category-filter" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sort-filter" name="sort">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="row">
    @forelse($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card h-100 product-card">
            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 180px; object-fit: cover;">
                @else
                    <i class="fas fa-image fa-3x text-muted"></i>
                @endif
            </div>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">{{ $product->name }}</h6>
                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                <div class="mb-2">
                    <small class="text-muted">
                        <i class="fas fa-store me-1"></i> {{ $product->merchantAccount->user->name ?? 'Unknown Merchant' }}
                    </small>
                    <br>
                    <small class="text-muted">
                        <i class="fas fa-tag me-1"></i> {{ $product->category->name ?? 'Uncategorized' }}
                    </small>
                    <br>
                    <small class="text-success">
                        <i class="fas fa-box me-1"></i> {{ $product->stock }} in stock
                    </small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="h5 text-primary mb-0">GH₵{{ number_format($product->price / 100, 2) }}</span>
                    <button class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->price / 100 }}" data-product-stock="{{ $product->stock }}">
                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-4"></i>
            <h4 class="text-muted">No products found</h4>
            <p class="text-muted">Try adjusting your search or filters</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($products->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endif

<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Added to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 id="modal-product-name">Product Name</h5>
                    <p class="text-muted">has been added to your cart!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Shopping</button>
                <a href="{{ route('customer.cart') }}" class="btn btn-primary">View Cart</a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
let cartCount = 0;

// Add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        const productPrice = this.dataset.productPrice;
        const productStock = parseInt(this.dataset.productStock);
        
        // Disable button during request
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Adding...';
        
        // Send AJAX request to add to cart
        fetch(`/customer/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            // Re-enable button
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-cart-plus me-1"></i> Add to Cart';
            
            if (data.success) {
                // Update cart count
                document.getElementById('cart-count').textContent = data.cart_count;
                
                // Show success modal
                document.getElementById('modal-product-name').textContent = productName;
                const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                modal.show();
            } else {
                alert(data.error || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            // Re-enable button
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-cart-plus me-1"></i> Add to Cart';
            console.error('Error:', error);
            alert('Failed to add product to cart');
        });
    });
});

// Search functionality
document.getElementById('search-input').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const searchTerm = this.value;
        const currentUrl = new URL(window.location);
        if (searchTerm) {
            currentUrl.searchParams.set('search', searchTerm);
        } else {
            currentUrl.searchParams.delete('search');
        }
        window.location.href = currentUrl.toString();
    }
});

// Category filter
document.getElementById('category-filter').addEventListener('change', function() {
    const category = this.value;
    const currentUrl = new URL(window.location);
    if (category) {
        currentUrl.searchParams.set('category', category);
    } else {
        currentUrl.searchParams.delete('category');
    }
    window.location.href = currentUrl.toString();
});

// Sort filter
document.getElementById('sort-filter').addEventListener('change', function() {
    const sort = this.value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('sort', sort);
    window.location.href = currentUrl.toString();
});

// Load initial cart count
fetch('/customer/cart', {
    method: 'GET',
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    }
})
.then(response => response.text())
.then(html => {
    // Extract cart count from response if needed
    // For now, we'll update this when items are added
})
.catch(error => {
    console.log('Could not load initial cart count');
});
</script>
@endsection
