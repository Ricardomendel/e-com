@extends('layouts.dashboard')

@section('title', 'Staff Dashboard')
@section('dashboard-type', 'Staff')
@section('page-title', 'Staff Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('staff.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.merchants') }}">
            <i class="fas fa-store me-2"></i> Merchants
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.orders') }}">
            <i class="fas fa-shopping-cart me-2"></i> Orders
        </a>
    </li>
@endsection

@section('content')
<!-- Welcome Message -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Welcome back, {{ auth()->user()->name }}!</strong> Here's your staff dashboard overview.
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
                        <h5 class="card-title">Total Merchants</h5>
                        <h3 class="mb-0">{{ $stats['total_merchants'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-store fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Active Merchants</h5>
                        <h3 class="mb-0">{{ $stats['active_merchants'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Pending Approvals</h5>
                        <h3 class="mb-0">{{ $stats['pending_merchants'] ?? 0 }}</h3>
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
                        <h5 class="card-title">Total Orders</h5>
                        <h3 class="mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Pending Merchant Approvals -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Merchant Approvals</h5>
                <a href="{{ route('staff.merchants') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($pending_merchants) && $pending_merchants->count() > 0)
                    @foreach($pending_merchants->take(5) as $merchant)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $merchant->name }}</strong><br>
                                <small class="text-muted">{{ $merchant->email }}</small><br>
                                <small class="text-muted">{{ $merchant->created_at->diffForHumans() }}</small>
                            </div>
                            <form method="POST" action="{{ route('staff.approve-merchant', $merchant) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this merchant?')">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">No pending merchant approvals</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="{{ route('staff.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recent_orders) && $recent_orders->count() > 0)
                    @foreach($recent_orders->take(5) as $order)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>#{{ $order->invoice ?? $order->id }}</strong><br>
                                <small class="text-muted">{{ $order->user->name ?? 'Guest' }}</small><br>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-end">
                                <strong>${{ number_format($order->total_price ?? 0, 2) }}</strong><br>
                                <span class="badge bg-{{ $order->status === 'SUCCESS' ? 'success' : ($order->status === 'PENDING' ? 'warning' : 'danger') }}">
                                    {{ $order->status ?? 'Pending' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-info mb-3"></i>
                        <p class="text-muted">No recent orders</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('staff.merchants') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-store me-2"></i> Manage Merchants
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('staff.orders') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-shopping-cart me-2"></i> View Orders
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="/admin/login" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-cog me-2"></i> Admin Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection