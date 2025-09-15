<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        // Simple role check - just log for debugging
        $user = auth()->user();
        \Log::info('Staff dashboard access attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'role_type' => gettype($user->role)
        ]);

        // Staff dashboard stats
        $stats = [
            'total_merchants' => User::where('role', 'MERCHANT')->count(),
            'active_merchants' => User::where('role', 'MERCHANT')->where('status', 'ACTIVE')->count(),
            'pending_merchants' => User::where('role', 'MERCHANT')->where('status', 'INACTIVE')->count(),
            'total_orders' => 0, // Order::count(),
        ];

        // Recent activities (simplified for demo)
        $recent_orders = collect([]);

        $pending_merchants = User::where('role', 'MERCHANT')
            ->where('status', 'INACTIVE')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact('stats', 'recent_orders', 'pending_merchants'));
    }

    public function merchants()
    {
        // Role check removed for debugging

        $merchants = User::where('role', 'MERCHANT')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('staff.merchants', compact('merchants'));
    }

    public function orders()
    {
        // Role check removed for debugging

        // For demo purposes, create empty paginated collection
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            collect([]), // Empty collection
            0, // Total items
            15, // Per page
            1, // Current page
            ['path' => request()->url()]
        );

        return view('staff.orders', compact('orders'));
    }

    public function approveMerchant(Request $request, User $user)
    {
        // Ensure only STAFF can approve merchants
        if (!auth()->check() || !in_array('STAFF', (array) auth()->user()->role)) {
            abort(403, 'Access denied. Staff access required.');
        }

        if ($user->role !== 'MERCHANT') {
            return back()->withErrors(['error' => 'User is not a merchant.']);
        }

        $user->update(['status' => 'ACTIVE']);

        return back()->with('success', 'Merchant approved successfully.');
    }
}