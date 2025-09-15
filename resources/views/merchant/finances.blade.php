@extends('layouts.dashboard')

@section('title', 'Financial Overview')
@section('dashboard-type', 'Merchant')
@section('page-title', 'Financial Overview')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merchant.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Finances</li>
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
        <a class="nav-link" href="{{ route('merchant.orders') }}">
            <i class="fas fa-shopping-cart me-2"></i> Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('merchant.finances') }}">
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
    <!-- Financial Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Sales</h5>
                            <h3 class="mb-0">GH₵{{ number_format($stats['total_sales'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Commission</h5>
                            <h3 class="mb-0">GH₵{{ number_format($stats['total_commission'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Pending Payouts</h5>
                            <h3 class="mb-0">GH₵{{ number_format($stats['pending_payouts'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">This Month</h5>
                            <h3 class="mb-0">GH₵{{ number_format($stats['this_month'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Transactions</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'KREDIT' ? 'success' : 'danger' }}">
                                            {{ $transaction->type === 'KREDIT' ? 'Credit' : 'Debit' }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->description ?? 'Transaction' }}</td>
                                    <td>
                                        @if($transaction->type === 'KREDIT')
                                            +GH₵{{ number_format($transaction->amount, 2) }}
                                        @else
                                            -GH₵{{ number_format($transaction->amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'SUCCESS' ? 'success' : 'warning' }}">
                                            {{ ucfirst(strtolower($transaction->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-receipt fa-2x text-muted mb-2"></i><br>
                                        <span class="text-muted">No transactions found</span><br>
                                        <small class="text-muted">Start selling to see your financial transactions here!</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Request -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Request Payout</h3>
                </div>
                <div class="card-body">
                    <form id="payoutForm">
                        <div class="form-group">
                            <label for="amount">Amount to Withdraw (GH₵)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">GH₵</span>
                                </div>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" max="{{ $stats['pending_payouts'] ?? 0 }}">
                            </div>
                            <small class="form-text text-muted">Available: GH₵{{ number_format($stats['pending_payouts'] ?? 0, 2) }}</small>
                        </div>
                        <div class="form-group">
                            <label for="method">Payout Method</label>
                            <select class="form-control" id="method" name="method">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="paypal">PayPal</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Request Payout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('payoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Payout request functionality - coming soon!');
});
</script>
@endsection
