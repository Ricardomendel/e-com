<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $merchant->merchantSetting->store_name ?? $merchant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{
            --primary: {{ $merchant->merchantSetting->primary_color ?? '#0ea5e9' }};
            --secondary: {{ $merchant->merchantSetting->secondary_color ?? '#111827' }};
            --font: {{ $merchant->merchantSetting->font_family ?? 'Inter, ui-sans-serif, system-ui' }};
        }
        body{font-family: var(--font)}
        .btn{background:var(--primary)}
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-[var(--secondary)] text-white">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center gap-3">
            @if($merchant->merchantSetting?->logo_path)
                <img class="h-9 w-9 rounded" src="{{ asset('storage/'.$merchant->merchantSetting->logo_path) }}" alt="logo">
            @endif
            <div class="flex-1">
                <h1 class="text-lg font-semibold">{{ $merchant->merchantSetting->store_name ?? $merchant->name }}</h1>
                <p class="text-xs/5 opacity-70">Welcome to our store</p>
            </div>
            <a href="{{ url('/store/'.$merchant->username.'/cart') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm hover:bg-white/20">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 2.293A1 1 0 0 0 6.618 17H19m-12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
                Cart
            </a>
        </div>
    </header>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Latest Products</h2>
                    <p class="text-sm text-gray-500">Hand picked items from {{ $merchant->name }}</p>
                </div>
                <a href="{{ url('/store/'.$merchant->username.'/products') }}" class="text-sm text-[var(--primary)] hover:underline">View all</a>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach($products as $p)
            <a href="{{ url('/store/'.$merchant->username.'/p/'.$p->slug) }}" class="group rounded-xl bg-white ring-1 ring-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                    <img class="h-full w-full object-cover group-hover:scale-105 transition" src="{{ optional($p->productImages()->first())->getImage() ?? asset('images/no-image.png') }}" alt="{{ $p->name }}">
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $p->name }}</h3>
                        <span class="shrink-0 rounded-full bg-[var(--primary)]/10 px-2 py-0.5 text-xs font-semibold text-[var(--primary)]">{{ currencyFormat($p->price) }}</span>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">Tap to view details</div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <footer class="border-t bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 text-center text-xs text-gray-500">© {{ date('Y') }} {{ $merchant->merchantSetting->store_name ?? $merchant->name }}</div>
    </footer>
</body>
</html>


