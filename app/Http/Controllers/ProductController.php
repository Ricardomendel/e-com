<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MerchantAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Ensure the user is authenticated (role-agnostic to avoid false 403s)
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'weight' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Get or create merchant account
            $merchantAccount = $user->merchantAccount;
            if (!$merchantAccount) {
                // Create merchant account if it doesn't exist
                $merchantAccount = MerchantAccount::create([
                    'user_id' => $user->id,
                    'name' => $user->name . "'s Store",
                    'slug' => Str::slug($user->name . "'s Store"),
                    'address' => 'Default Address',
                    'balance' => 0,
                ]);
            }

            // Get or create category (lookup by slug to avoid case-related duplicates)
            $categorySlug = Str::slug($validated['category']);
            $category = Category::where('slug', $categorySlug)->first();
            
            if (!$category) {
                $category = Category::create([
                    'name' => $validated['category'],
                    'slug' => $categorySlug,
                    'created_by' => $user->id,
                ]);
            }

            // Create slug from name
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;
            
            // Ensure unique slug
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/products'), $imageName);
                $imagePath = 'storage/products/' . $imageName;
            }

            // Create the product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'price' => (int)($validated['price'] * 100), // Store price in cents
                'stock' => $validated['stock'],
                'weight' => (int)(($validated['weight'] ?? 0) * 100), // Store weight in grams
                'category_id' => $category->id,
                'merchant_account_id' => $merchantAccount->id,
                'image' => $imagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price / 100,
                    'stock' => $product->stock,
                    'category' => $category->name,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Product creation error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display products for the authenticated merchant
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $merchantAccount = $user->merchantAccount;
        if (!$merchantAccount) {
            return response()->json(['products' => []]);
        }

        $products = Product::where('merchant_account_id', $merchantAccount->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        
        // Ensure user owns this product
        if (!$user->merchantAccount || $product->merchant_account_id !== $user->merchantAccount->id) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => (int)($validated['price'] * 100),
            'stock' => $validated['stock'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $user = auth()->user();
        
        // Ensure user owns this product
        if (!$user->merchantAccount || $product->merchant_account_id !== $user->merchantAccount->id) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }
}
