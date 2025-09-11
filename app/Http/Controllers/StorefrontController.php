<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    private function merchantByUsername(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
    }

    public function home(string $username)
    {
        $merchant = $this->merchantByUsername($username)->load('merchantSetting');
        $products = Product::whereHas('merchantAccount', fn ($q) => $q->where('user_id', $merchant->id))
            ->latest()->limit(8)->get();

        return view('store.home', compact('merchant', 'products'));
    }

    public function products(string $username)
    {
        $merchant = $this->merchantByUsername($username)->load('merchantSetting');
        $products = Product::whereHas('merchantAccount', fn ($q) => $q->where('user_id', $merchant->id))
            ->latest()->paginate(12);

        return view('store.products', compact('merchant', 'products'));
    }

    public function product(string $username, string $slug)
    {
        $merchant = $this->merchantByUsername($username)->load('merchantSetting');
        $product = Product::where('slug', $slug)
            ->whereHas('merchantAccount', fn ($q) => $q->where('user_id', $merchant->id))
            ->firstOrFail();

        return view('store.product', compact('merchant', 'product'));
    }
}


