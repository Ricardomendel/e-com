<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StoreCheckoutController extends Controller
{
    private function cartKey(string $username): string
    {
        return 'cart_' . $username;
    }

    private function merchant(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
    }

    public function show(string $username): View
    {
        $merchant = $this->merchant($username)->load('merchantSetting');
        $items = session($this->cartKey($username), []);
        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']);
        $shipping = 0; $tax = 0; $total = $subtotal + $shipping + $tax;
        return view('store.checkout', compact('merchant', 'items', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function place(Request $request, string $username): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'payment' => 'required|in:cod',
        ]);

        $key = $this->cartKey($username);
        $items = session($key, []);
        if (empty($items)) {
            return redirect()->to(url('/store/' . $username . '/cart'));
        }

        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']);

        // Determine the customer: use logged-in user or create/find by email
        $customer = auth()->user();
        if (!$customer) {
            $customer = User::firstWhere('email', $request->input('email'));
        }
        if (!$customer) {
            $baseUsername = str($request->input('name'))
                ->slug('-')
                ->limit(30, '')
                ->value();
            $candidate = $baseUsername !== '' ? $baseUsername : ('customer-' . Str::lower(Str::random(6)));
            // Ensure username uniqueness (best-effort)
            while (User::where('username', $candidate)->exists()) {
                $candidate = $baseUsername . '-' . Str::lower(Str::random(3));
            }

            $customer = User::create([
                'name' => $request->input('name'),
                'username' => $candidate,
                'email' => $request->input('email'),
                'phone' => preg_replace('/[^0-9]/', '', (string) $request->input('phone')) ?: (string) random_int(1000000000, 1999999999),
                'role' => 'CUSTOMER',
                'status' => 'ACTIVE',
                'address' => $request->input('address'),
                'password' => Hash::make(Str::random(12)),
            ]);
        }

        // Create order
        $order = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'LARA-' . date('ymd') . Str::upper(Str::random(8)),
            'total_price' => (int) $subtotal,
            'courier_services' => json_encode(['COD']),
            'status' => 'PENDING',
        ]);

        // Attach products
        foreach ($items as $pid => $line) {
            $order->products()->attach((int) $pid, [
                'quantity' => (int) $line['quantity'],
                'total_price' => (int) ($line['price'] * $line['quantity']),
            ]);
        }

        // Clear cart and thank you
        session()->forget($key);
        return redirect()->to(url('/store/' . $username . '/thank-you'));
    }

    public function thankYou(string $username): View
    {
        $merchant = $this->merchant($username)->load('merchantSetting');
        return view('store.thankyou', compact('merchant'));
    }
}


