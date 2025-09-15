@extends('layouts.dashboard')

@section('title', 'Shopping Cart')
@section('dashboard-type', 'Customer')
@section('page-title', 'Shopping Cart')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.shop') }}">Shop</a></li>
    <li class="breadcrumb-item active">Cart</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.shop') }}">
            <i class="fas fa-shopping-bag me-2"></i> Shop
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('customer.cart') }}">
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
    <a href="{{ route('customer.shop') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Continue Shopping
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Cart Items</h5>
            </div>
            <div class="card-body">
                @if(isset($cart_items) && $cart_items->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                @foreach($cart_items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3" style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->merchantAccount->user->name ?? 'Unknown Merchant' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>GH₵{{ number_format($item->product->price / 100, 2) }}</td>
                                    <td>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity({{ $item->product->id }}, -1)">-</button>
                                            <input type="number" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1" id="qty-{{ $item->product->id }}" onchange="updateQuantity({{ $item->product->id }}, 0)">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity({{ $item->product->id }}, 1)">+</button>
                                        </div>
                                    </td>
                                    <td>GH₵{{ number_format($item->subtotal, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="removeItem({{ $item->product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Your cart is empty</h4>
                        <p class="text-muted mb-4">Add some products to your cart to get started!</p>
                        <a href="{{ route('customer.shop') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span id="subtotal">GH₵{{ number_format($cart_total ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span id="shipping">GH₵5.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax:</span>
                    <span id="tax">GH₵{{ number_format(($cart_total ?? 0) * 0.1, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong id="total">GH₵{{ number_format(($cart_total ?? 0) + 5 + (($cart_total ?? 0) * 0.1), 2) }}</strong>
                </div>
                
                @if(isset($cart_items) && $cart_items->count() > 0)
                    <button class="btn btn-primary w-100 mb-2" onclick="proceedToCheckout()">
                        <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                    </button>
                @else
                    <button class="btn btn-primary w-100 mb-2" disabled>
                        <i class="fas fa-credit-card me-2"></i> Cart is Empty
                    </button>
                @endif
                
                <a href="{{ route('customer.shop') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                </a>
            </div>
        </div>
        
        <!-- Promo Code -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Promo Code</h6>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Enter promo code" id="promo-code">
                    <button class="btn btn-outline-secondary" type="button" onclick="applyPromoCode()">Apply</button>
                </div>
                <small class="text-muted">Enter a valid promo code to get discount</small>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="checkoutForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address *</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="zipCode" class="form-label">ZIP Code *</label>
                            <input type="text" class="form-control" id="zipCode" name="zipCode" required>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6>Payment Method</h6>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="credit_card" checked>
                            <label class="form-check-label" for="creditCard">
                                <i class="fas fa-credit-card me-2"></i> Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal me-2"></i> PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cashOnDelivery" value="cod">
                            <label class="form-check-label" for="cashOnDelivery">
                                <i class="fas fa-money-bill me-2"></i> Cash on Delivery
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-1"></i> Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
function updateQuantity(itemId, change) {
    const qtyInput = document.getElementById(`qty-${itemId}`);
    let currentQty = parseInt(qtyInput.value);
    let newQty;
    
    if (change === 0) {
        // Direct input change
        newQty = currentQty;
    } else {
        // Button click
        newQty = currentQty + change;
    }
    
    if (newQty < 1) {
        removeItem(itemId);
        return;
    }
    
    // Send AJAX request to update cart
    fetch(`/customer/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: newQty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to update totals
            location.reload();
        } else {
            alert(data.error || 'Failed to update cart');
            qtyInput.value = currentQty; // Reset to previous value
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update cart');
        qtyInput.value = currentQty; // Reset to previous value
    });
}

function removeItem(itemId) {
    if (confirm('Remove this item from your cart?')) {
        fetch(`/customer/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to update cart
                location.reload();
            } else {
                alert(data.error || 'Failed to remove item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to remove item');
        });
    }
}

function updateCartTotals() {
    // This would calculate based on actual cart items
    // For demo purposes, we'll just show static values
    console.log('Cart totals updated');
}

function applyPromoCode() {
    const promoCode = document.getElementById('promo-code').value;
    
    if (promoCode.toLowerCase() === 'save10') {
        alert('Promo code applied! 10% discount added.');
    } else if (promoCode) {
        alert('Invalid promo code. Try "SAVE10" for 10% off!');
    }
}

function proceedToCheckout() {
    const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    modal.show();
}

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const orderData = Object.fromEntries(formData);
    
    // Show success message
    alert('Order placed successfully!\n\nThis is a demo. In a real application, this would process the payment and create the order.');
    
    // Close modal and redirect
    bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
    
    // In a real app, you would redirect to order confirmation page
    setTimeout(() => {
        window.location.href = '{{ route("customer.orders") }}';
    }, 1000);
});
</script>
@endsection
@endsection
