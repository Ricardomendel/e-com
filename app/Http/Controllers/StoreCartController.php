<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreCartController extends Controller
{
    private function cartKey(string $username): string
    {
        return 'cart_' . $username;
    }

    private function merchant(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
    }

    public function view(string $username): View
    {
        $merchant = $this->merchant($username)->load('merchantSetting');
        $items = session($this->cartKey($username), []);
        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']);
        return view('store.cart', compact('merchant', 'items', 'subtotal'));
    }

    public function add(Request $request, string $username): RedirectResponse
    {
        $request->validate(['product_id' => 'required|integer', 'quantity' => 'nullable|integer|min:1']);
        $merchant = $this->merchant($username);
        $product = Product::whereHas('merchantAccount', fn ($q) => $q->where('user_id', $merchant->id))
            ->findOrFail($request->input('product_id'));

        $key = $this->cartKey($username);
        $items = session($key, []);
        $pid = (string) $product->id;
        $qty = max(1, (int) $request->input('quantity', 1));

        if (isset($items[$pid])) {
            $items[$pid]['quantity'] += $qty;
        } else {
            $items[$pid] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (int) $product->price,
                'quantity' => $qty,
                'image' => optional($product->productImages()->first())->getImage(),
                'slug' => $product->slug,
            ];
        }
        session([$key => $items]);
        return redirect()->to(url('/store/' . $username . '/cart'));
    }

    public function update(Request $request, string $username): RedirectResponse
    {
        $request->validate(['lines' => 'array']);
        $key = $this->cartKey($username);
        $items = session($key, []);
        foreach ((array) $request->input('lines', []) as $pid => $qty) {
            if (isset($items[$pid])) {
                $q = max(0, (int) $qty);
                if ($q === 0) unset($items[$pid]); else $items[$pid]['quantity'] = $q;
            }
        }
        session([$key => $items]);
        return back();
    }

    public function remove(Request $request, string $username): RedirectResponse
    {
        $request->validate(['product_id' => 'required']);
        $key = $this->cartKey($username);
        $items = session($key, []);
        unset($items[(string) $request->input('product_id')]);
        session([$key => $items]);
        return back();
    }
}


