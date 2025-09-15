@extends('layouts.dashboard')

@section('title', 'Customer Dashboard')
@section('dashboard-type', 'Customer')
@section('page-title', 'My Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('customer.dashboard') }}">
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

@section('content')
<!-- Welcome Message -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info" role="alert">
            <i class="fas fa-shopping-bag me-2"></i>
            <strong>Welcome {{ auth()->user()->name }}!</strong> Start shopping from our amazing merchants.
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Orders</h5>
                        <h3 class="mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Pending Orders</h5>
                        <h3 class="mb-0">{{ $stats['pending_orders'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Completed Orders</h5>
                        <h3 class="mb-0">{{ $stats['completed_orders'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Spent</h5>
                        <h3 class="mb-0">GH₵{{ number_format($stats['total_spent'] ?? 0, 2) }}</h3>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('customer.shop') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i> Browse Products
                    </a>
                    <a href="{{ route('customer.cart') }}" class="btn btn-outline-info">
                        <i class="fas fa-shopping-cart me-2"></i> View Cart
                    </a>
                    <a href="{{ route('customer.orders') }}" class="btn btn-outline-success">
                        <i class="fas fa-list me-2"></i> My Orders
                    </a>
                    <a href="{{ route('customer.profile') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Account Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Account Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-check text-success fa-2x me-3"></i>
                    <div>
                        <strong>Account Active</strong><br>
                        <small class="text-muted">You can browse and purchase products</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="{{ route('customer.orders') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
            </div>
            <div class="card-body">
                @if(isset($recent_orders) && $recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Merchant</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders->take(5) as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->merchant_name ?? 'Store' }}</td>
                                    <td>GH₵{{ number_format($order->total ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No orders yet!</h5>
                        <p class="text-muted mb-4">Start shopping to see your orders here.</p>
                        <a href="{{ route('customer.shop') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Featured Categories -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Shop by Category</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-laptop fa-3x text-primary mb-3"></i>
                                <h6 class="card-title">Electronics</h6>
                                <a href="{{ route('customer.shop') }}?category=electronics" class="btn btn-outline-primary btn-sm">Browse</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-tshirt fa-3x text-success mb-3"></i>
                                <h6 class="card-title">Clothing</h6>
                                <a href="{{ route('customer.shop') }}?category=clothing" class="btn btn-outline-success btn-sm">Browse</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-home fa-3x text-info mb-3"></i>
                                <h6 class="card-title">Home & Garden</h6>
                                <a href="{{ route('customer.shop') }}?category=home" class="btn btn-outline-info btn-sm">Browse</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-book fa-3x text-warning mb-3"></i>
                                <h6 class="card-title">Books</h6>
                                <a href="{{ route('customer.shop') }}?category=books" class="btn btn-outline-warning btn-sm">Browse</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
