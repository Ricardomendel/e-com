<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout - {{ $merchant->merchantSetting->store_name ?? $merchant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{
            --primary: {{ $merchant->merchantSetting->primary_color ?? '#0ea5e9' }};
            --secondary: {{ $merchant->merchantSetting->secondary_color ?? '#111827' }};
            --font: {{ $merchant->merchantSetting->font_family ?? 'Inter, ui-sans-serif, system-ui' }};
        }
        body{font-family: var(--font)}
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <meta name="description" content="Checkout">
    <meta name="robots" content="noindex">
    <meta name="turbo-visit-control" content="reload">
</head>
<body class="bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Checkout</h2>
                <p class="text-sm text-gray-500">Complete your order below</p>
            </div>
            <a href="{{ url('/store/'.$merchant->username.'/cart') }}" class="text-sm text-[var(--primary)] hover:underline">Back to Cart</a>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2 rounded-xl bg-white ring-1 ring-gray-100 shadow-sm">
                <form method="post" action="{{ url('/store/'.$merchant->username.'/checkout') }}" class="p-4 space-y-4">
                    @csrf
                    @if ($errors->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Name</label>
                            <input class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-[var(--primary)] focus:outline-none" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Email</label>
                            <input class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-[var(--primary)] focus:outline-none" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Phone</label>
                            <input class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-[var(--primary)] focus:outline-none" name="phone" value="{{ old('phone') }}" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Payment</label>
                            <select class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-[var(--primary)] focus:outline-none" name="payment">
                                <option value="cod" {{ old('payment')==='cod' ? 'selected' : '' }}>Cash on Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Address</label>
                        <textarea class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-[var(--primary)] focus:outline-none" name="address" rows="3" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-medium text-white">Place Order</button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl bg-white p-4 ring-1 ring-gray-100 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Order Summary</h3>
                <div class="mt-3 divide-y divide-gray-100 text-sm">
                    @foreach($items as $line)
                        <div class="flex items-center justify-between py-2">
                            <div class="max-w-[60%] truncate pr-3 text-gray-700">{{ $line['name'] }}</div>
                            <div class="shrink-0 text-gray-500">× {{ $line['quantity'] }}</div>
                            <div class="shrink-0 font-medium text-gray-900">{{ currencyFormat($line['price'] * $line['quantity']) }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between"><span>Subtotal</span><span>{{ currencyFormat((int) $subtotal) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>{{ currencyFormat((int) $shipping) }}</span></div>
                    <div class="flex justify-between"><span>Tax</span><span>{{ currencyFormat((int) $tax) }}</span></div>
                    <div class="flex justify-between font-semibold text-gray-900"><span>Total</span><span>{{ currencyFormat((int) $total) }}</span></div>
                </div>

                <div class="mt-3 rounded-lg bg-emerald-50 p-3 text-xs text-emerald-700">Payment method: Cash on Delivery</div>
            </div>
        </div>
    </div>
</body>
</html>


