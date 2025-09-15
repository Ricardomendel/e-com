@extends('layouts.dashboard')

@section('title', 'Merchant Dashboard')
@section('dashboard-type', 'Merchant')
@section('page-title', 'Merchant Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('merchant.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.products') }}">
            <i class="fas fa-box me-2"></i> Products
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.orders') }}">
            <i class="fas fa-shopping-cart me-2"></i> Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.finances') }}">
            <i class="fas fa-chart-line me-2"></i> Finances
        </a>
    </li>
@endsection

@section('content')
<!-- Welcome Message -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-success" role="alert">
            <i class="fas fa-store me-2"></i>
            <strong>Welcome to your store, {{ auth()->user()->name }}!</strong> Manage your products and track your sales.
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
                        <h5 class="card-title">Total Products</h5>
                        <h3 class="mb-0">{{ $stats['total_products'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-box fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Sales</h5>
                        <h3 class="mb-0">${{ number_format($stats['total_sales'] ?? 0, 2) }}</h3>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x"></i>
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
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">This Month</h5>
                        <h3 class="mb-0">${{ number_format($stats['this_month'] ?? 0, 2) }}</h3>
                    </div>
                    <i class="fas fa-calendar fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="{{ route('merchant.orders') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
            </div>
            <div class="card-body">
                @if(isset($recent_orders) && $recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders->take(5) as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>${{ number_format($order->total ?? 0, 2) }}</td>
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
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No orders yet. Start selling to see orders here!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('merchant.products') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Product
                    </a>
                    <a href="{{ route('merchant.orders') }}" class="btn btn-outline-info">
                        <i class="fas fa-list me-2"></i> Manage Orders
                    </a>
                    <a href="{{ route('merchant.finances') }}" class="btn btn-outline-success">
                        <i class="fas fa-chart-line me-2"></i> View Finances
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Account Status -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Account Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success fa-2x me-3"></i>
                    <div>
                        <strong>Account Active</strong><br>
                        <small class="text-muted">You can sell products and receive payments</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Chart Placeholder -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales Performance</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Sales chart will be available soon. Keep selling to see your performance!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection