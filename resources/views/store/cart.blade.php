<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cart - {{ $merchant->merchantSetting->store_name ?? $merchant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Cart</h2>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2 rounded-xl bg-white ring-1 ring-gray-100 shadow-sm">
                <form method="post" action="{{ url('/store/'.$merchant->username.'/cart/update') }}" class="p-4">
                    @csrf
                    <table class="w-full border-separate [border-spacing:0 8px]">
                        <thead>
                            <tr class="text-xs text-gray-500">
                                <th class="text-left font-medium">Product</th>
                                <th class="text-right font-medium">Price</th>
                                <th class="text-right font-medium">Qty</th>
                                <th class="text-right font-medium">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $line)
                            <tr class="bg-white">
                                <td class="rounded-l-xl">
                                    <div class="flex items-center gap-3">
                                        <img class="h-12 w-12 rounded object-cover ring-1 ring-gray-100" src="{{ $line['image'] ?? '' }}" alt="">
                                        <div class="text-sm font-medium text-gray-900">{{ $line['name'] }}</div>
                                    </div>
                                </td>
                                <td class="text-right text-sm text-gray-700">{{ currencyFormat($line['price']) }}</td>
                                <td class="text-right">
                                    <input class="w-20 rounded border border-gray-200 px-2 py-1 text-right" type="number" name="lines[{{ $line['product_id'] }}]" value="{{ $line['quantity'] }}" min="0">
                                </td>
                                <td class="text-right text-sm font-semibold text-gray-900">{{ currencyFormat($line['price'] * $line['quantity']) }}</td>
                                <td class="rounded-r-xl text-right">
                                    <form method="post" action="{{ url('/store/'.$merchant->username.'/cart/remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $line['product_id'] }}">
                                        <button class="text-xs text-red-600 hover:underline" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-sm text-gray-500">Your cart is empty.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ url('/store/'.$merchant->username) }}" class="text-sm text-[var(--primary)] hover:underline">Continue Shopping</a>
                        <div class="flex gap-2 sm:justify-end">
                            <button class="rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-medium text-white" type="submit">Update Cart</button>
                            @if(($subtotal ?? 0) > 0)
                                <a href="{{ url('/store/'.$merchant->username.'/checkout') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white">Proceed to Checkout</a>
                            @else
                                <span class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed" title="Add items to proceed">Proceed to Checkout</span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="rounded-xl bg-white p-4 ring-1 ring-gray-100 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Order Summary</h3>
                <div class="mt-3 space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between"><span>Subtotal</span><span>{{ currencyFormat((int) $subtotal) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>{{ currencyFormat(0) }}</span></div>
                    <div class="flex justify-between font-semibold text-gray-900"><span>Total</span><span>{{ currencyFormat((int) $subtotal) }}</span></div>
                </div>
                @if(($subtotal ?? 0) > 0)
                    <a href="{{ url('/store/'.$merchant->username.'/checkout') }}" class="mt-4 block rounded-lg bg-[var(--primary)] px-4 py-2 text-center text-sm font-medium text-white">Checkout</a>
                @else
                    <span class="mt-4 block rounded-lg bg-gray-200 px-4 py-2 text-center text-sm font-medium text-gray-500 cursor-not-allowed" title="Add items to proceed">Checkout</span>
                @endif
            </div>
        </div>
    </div>
</body>
</html>


