<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = auth()->user();

        // Customer dashboard stats
        $stats = [
            'total_orders' => 0, // Order::where('user_id', $user->id)->count(),
            'pending_orders' => 0, // Order::where('user_id', $user->id)->where('status', 'PENDING')->count(),
            'completed_orders' => 0, // Order::where('user_id', $user->id)->where('status', 'SUCCESS')->count(),
            'total_spent' => 0, // Order::where('user_id', $user->id)->where('status', 'SUCCESS')->sum('total_price'),
        ];

        // Recent orders (simplified for demo)
        $recent_orders = collect([]);

        return view('customer.dashboard', compact('stats', 'recent_orders'));
    }

    public function shop(Request $request)
    {
        // Browse all products from merchants
        $query = Product::with(['category', 'merchantAccount.user'])
            ->where('stock', '>', 0); // Only show products in stock

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Get categories for filter dropdown
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('customer.shop', compact('products', 'categories'));
    }

    public function cart()
    {
        // Get cart from session
        $cart = session()->get('cart', []);
        $cart_items = collect();
        $cart_total = 0;

        // Convert cart array to collection with product details
        foreach ($cart as $productId => $item) {
            $product = Product::with(['category', 'merchantAccount.user'])->find($productId);
            if ($product) {
                $cartItem = (object)[
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => ($product->price / 100) * $item['quantity']
                ];
                $cart_items->push($cartItem);
                $cart_total += $cartItem->subtotal;
            }
        }

        return view('customer.cart', compact('cart_items', 'cart_total'));
    }

    public function orders()
    {
        // Customer orders
        // For demo purposes, create empty paginated collection
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            collect([]), // Empty collection
            0, // Total items
            15, // Per page
            1, // Current page
            ['path' => request()->url()]
        );

        return view('customer.orders', compact('orders'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }

    public function addToCart(Request $request, $productId)
    {
        // Find the product by ID
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $quantity = $request->get('quantity', 1);
        
        // Validate quantity
        if ($quantity <= 0 || $quantity > $product->stock) {
            return response()->json([
                'error' => 'Invalid quantity. Available stock: ' . $product->stock
            ], 400);
        }

        // Get current cart from session
        $cart = session()->get('cart', []);

        // If product already in cart, update quantity
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'error' => 'Cannot add more items. Available stock: ' . $product->stock
                ], 400);
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            // Add new product to cart
            $cart[$product->id] = [
                'quantity' => $quantity,
                'price' => $product->price / 100 // Store price in dollars for session
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    public function updateCart(Request $request, $productId)
    {
        // Find the product by ID
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $quantity = $request->get('quantity', 1);
        
        // Validate quantity
        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }
        
        if ($quantity > $product->stock) {
            return response()->json([
                'error' => 'Invalid quantity. Available stock: ' . $product->stock
            ], 400);
        }

        // Get current cart from session
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return response()->json(['error' => 'Product not found in cart'], 404);
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart!',
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return response()->json(['error' => 'Product not found in cart'], 404);
    }

    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!',
            'cart_count' => 0
        ]);
    }
}
