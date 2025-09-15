<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Finance;
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
        \Log::info('Merchant dashboard access attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'role_type' => gettype($user->role)
        ]);

        $user = auth()->user();

        // Merchant dashboard stats
        $stats = [
            'total_products' => 0, // Product::where('user_id', $user->id)->count(),
            'total_sales' => 0, // Finance::where('user_id', $user->id)->where('type', 'KREDIT')->where('status', 'SUCCESS')->sum('amount'),
            'pending_orders' => 0, // Order::whereHas('products', function($q) use ($user) { $q->where('user_id', $user->id); })->where('status', 'PENDING')->count(),
            'this_month' => 0, // Finance::where('user_id', $user->id)->where('type', 'KREDIT')->where('status', 'SUCCESS')->whereMonth('created_at', now()->month)->sum('amount'),
        ];

        // Recent activities (simplified for demo)
        $recent_orders = collect([]); // Empty collection for demo
        $recent_products = collect([]); // Empty collection for demo

        return view('merchant.dashboard', compact('stats', 'recent_orders', 'recent_products'));
    }

    public function products()
    {
        $user = auth()->user();
        
        // Get merchant's products
        $merchantAccount = $user->merchantAccount;
        if ($merchantAccount) {
            $products = \App\Models\Product::where('merchant_account_id', $merchantAccount->id)
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Create empty paginated collection if no merchant account
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // Empty collection
                0, // Total items
                15, // Per page
                1, // Current page
                ['path' => request()->url()]
            );
        }

        return view('merchant.products', compact('products'));
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

        return view('merchant.orders', compact('orders'));
    }

    public function finances()
    {
        // Role check removed for debugging

        $user = auth()->user();

        // Financial statistics (demo data)
        $stats = [
            'total_sales' => 0,
            'total_commission' => 0,
            'pending_payouts' => 0,
            'this_month' => 0,
        ];

        // For demo purposes, create empty paginated collection
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            collect([]), // Empty collection
            0, // Total items
            15, // Per page
            1, // Current page
            ['path' => request()->url()]
        );

        return view('merchant.finances', compact('stats', 'transactions'));
    }
}