@extends('layouts.dashboard')

@section('title', 'Orders Management')
@section('dashboard-type', 'Staff')
@section('page-title', 'Orders Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.merchants') }}">
            <i class="fas fa-store me-2"></i> Merchants
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('staff.orders') }}">
            <i class="fas fa-shopping-cart me-2"></i> Orders
        </a>
    </li>
@endsection

@section('header-actions')
    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orders Management</h3>
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
                                        <button class="btn btn-sm btn-primary" onclick="updateOrderStatus({{ $order->id }})">
                                            Update
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
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

function updateOrderStatus(id) {
    alert('Update order status for ID: ' + id);
}
</script>
@endsection
