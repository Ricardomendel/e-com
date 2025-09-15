@extends('layouts.dashboard')

@section('title', 'My Orders')
@section('dashboard-type', 'Merchant')
@section('page-title', 'My Orders')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merchant.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.products') }}">
            <i class="fas fa-box me-2"></i> Products
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('merchant.orders') }}">
            <i class="fas fa-shopping-cart me-2"></i> Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.finances') }}">
            <i class="fas fa-chart-line me-2"></i> Finances
        </a>
    </li>
@endsection

@section('header-actions')
    <a href="{{ route('merchant.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Orders</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Commission</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>
                                        @if($order->products->count() > 0)
                                            {{ $order->products->count() }} item(s)
                                        @else
                                            No products
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->total ?? 0, 2) }}</td>
                                    <td>${{ number_format(($order->total ?? 0) * 0.1, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewOrder({{ $order->id }})">
                                            View
                                        </button>
                                        @if($order->status === 'pending')
                                        <button class="btn btn-sm btn-success" onclick="fulfillOrder({{ $order->id }})">
                                            Fulfill
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewOrder(id) {
    alert('View order details for ID: ' + id);
}

function fulfillOrder(id) {
    if (confirm('Mark this order as fulfilled?')) {
        alert('Fulfill order for ID: ' + id);
    }
}
</script>
@endsection
