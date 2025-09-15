@extends('layouts.dashboard')

@section('title', 'Manage Merchants')
@section('dashboard-type', 'Staff')
@section('page-title', 'Manage Merchants')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Merchants</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('staff.merchants') }}">
            <i class="fas fa-store me-2"></i> Merchants
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.orders') }}">
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
                    <h3 class="card-title">Merchants Management</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>City</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($merchants as $merchant)
                                <tr>
                                    <td>{{ $merchant->name }}</td>
                                    <td>{{ $merchant->email }}</td>
                                    <td>{{ $merchant->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $merchant->status === 'ACTIVE' ? 'success' : ($merchant->status === 'PENDING' ? 'warning' : 'danger') }}">
                                            {{ $merchant->status }}
                                        </span>
                                    </td>
                                    <td>{{ $merchant->city->name ?? 'N/A' }}</td>
                                    <td>{{ $merchant->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($merchant->status === 'PENDING')
                                        <form action="{{ route('staff.approve-merchant', $merchant) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this merchant?')">
                                                Approve
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <button class="btn btn-sm btn-info" onclick="viewMerchant({{ $merchant->id }})">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No merchants found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $merchants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewMerchant(id) {
    // Add modal or redirect logic here
    alert('View merchant details for ID: ' + id);
}
</script>
@endsection
