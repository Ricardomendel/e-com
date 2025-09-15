@extends('layouts.dashboard')

@section('title', 'My Orders')
@section('dashboard-type', 'Customer')
@section('page-title', 'My Orders')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
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
        <a class="nav-link" href="{{ route('customer.cart') }}">
            <i class="fas fa-shopping-cart me-2"></i> Cart
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('customer.orders') }}">
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
    <a href="{{ route('customer.shop') }}" class="btn btn-primary">
        <i class="fas fa-shopping-bag me-1"></i> Continue Shopping
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order History</h5>
            </div>
            <div class="card-body">
                @if(isset($orders) && $orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($order->products && $order->products->count() > 0)
                                            {{ $order->products->count() }} item(s)
                                        @else
                                            1 item
                                        @endif
                                    </td>
                                    <td>GH₵{{ number_format($order->total ?? 99.99, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'shipped' ? 'info' : 'warning') }}">
                                            {{ ucfirst($order->status ?? 'processing') }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewOrder({{ $order->id }})">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                        @if(($order->status ?? 'processing') === 'delivered')
                                        <button class="btn btn-sm btn-outline-success" onclick="reorder({{ $order->id }})">
                                            <i class="fas fa-redo me-1"></i> Reorder
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No orders yet</h4>
                        <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                        <a href="{{ route('customer.shop') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p><strong>Order ID:</strong> <span id="modal-order-id">#12345</span></p>
                        <p><strong>Order Date:</strong> <span id="modal-order-date">Jan 15, 2024</span></p>
                        <p><strong>Status:</strong> <span id="modal-order-status" class="badge bg-success">Delivered</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Shipping Information</h6>
                        <p><strong>Address:</strong><br>
                        <span id="modal-shipping-address">
                            123 Main Street<br>
                            City, State 12345
                        </span></p>
                    </div>
                </div>
                
                <h6>Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modal-order-items">
                            <tr>
                                <td>Sample Product</td>
                                <td>1</td>
                                <td>$29.99</td>
                                <td>$29.99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td class="text-end"><span id="modal-subtotal">$29.99</span></td>
                            </tr>
                            <tr>
                                <td><strong>Shipping:</strong></td>
                                <td class="text-end"><span id="modal-shipping">$5.00</span></td>
                            </tr>
                            <tr>
                                <td><strong>Tax:</strong></td>
                                <td class="text-end"><span id="modal-tax">$3.00</span></td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong><span id="modal-total">$37.99</span></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="trackOrder()">
                    <i class="fas fa-truck me-1"></i> Track Order
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function viewOrder(orderId) {
    // In a real app, you would fetch order details via AJAX
    console.log('Viewing order:', orderId);
    
    // Update modal with order details (demo data)
    document.getElementById('modal-order-id').textContent = `#${orderId}`;
    document.getElementById('modal-order-date').textContent = 'Jan 15, 2024';
    document.getElementById('modal-order-status').textContent = 'Delivered';
    document.getElementById('modal-order-status').className = 'badge bg-success';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    modal.show();
}

function reorder(orderId) {
    if (confirm('Add all items from this order to your cart?')) {
        alert(`Items from order #${orderId} have been added to your cart!`);
        // In a real app, you would add items to cart and redirect
        console.log('Reordering:', orderId);
    }
}

function trackOrder() {
    alert('Order tracking information:\n\n• Order confirmed - Jan 15, 2024\n• Shipped - Jan 16, 2024\n• Out for delivery - Jan 18, 2024\n• Delivered - Jan 18, 2024');
}

// Filter orders by status (demo)
function filterOrders(status) {
    console.log('Filtering orders by status:', status);
    // In a real app, you would filter the orders table
}
</script>
@endsection
@endsection
