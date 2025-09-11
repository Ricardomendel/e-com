<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $product->name }} - {{ $merchant->merchantSetting->store_name ?? $merchant->name }}</title>
    <style>
        :root{ --primary: {{ $merchant->merchantSetting->primary_color ?? '#0ea5e9' }}; --font: {{ $merchant->merchantSetting->font_family ?? 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif' }}; }
        body{font-family: var(--font); margin:0; background:#fafafa}
        .container{max-width:1100px;margin:0 auto;padding:20px}
        .wrap{display:grid;grid-template-columns: 1.1fr .9fr; gap:28px}
        .card{background:white;border:1px solid #e5e7eb;border-radius:12px;padding:16px}
        .main-img{width:100%;height:460px;object-fit:cover;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:10px}
        .thumbs{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
        .thumbs img{width:80px;height:80px;object-fit:cover;border:2px solid transparent;border-radius:8px;cursor:pointer;background:#f9fafb}
        .thumbs img.active{border-color:var(--primary)}
        h1{margin:0 0 6px 0}
        .price{color:var(--primary);font-weight:800;font-size:22px;margin:6px 0 12px 0}
        .muted{color:#6b7280}
        .row{display:flex;gap:12px;align-items:center;margin-top:12px}
        input[type=number]{width:90px;padding:8px;border:1px solid #e5e7eb;border-radius:8px}
        button{background:var(--primary);color:white;border:none;padding:12px 16px;border-radius:10px;cursor:pointer}
        a{color:inherit;text-decoration:none}
        .crumb{margin-bottom:12px}
    </style>
</head>
<body>
    <div class="container">
        <div class="crumb"><a href="{{ url('/store/'.$merchant->username) }}">← Back to store</a></div>
        <div class="wrap">
            <div class="card">
                @php($first = optional($product->productImages()->first())->getImage() ?? asset('images/no-image.png'))
                <img id="mainImage" class="main-img" src="{{ $first }}" alt="{{ $product->name }}">
                <div class="thumbs" id="thumbs">
                    @forelse($product->productImages as $img)
                        @php($src = $img->getImage())
                        <img src="{{ $src }}" data-src="{{ $src }}" alt="thumb">
                    @empty
                        <img src="{{ $first }}" data-src="{{ $first }}" class="active" alt="thumb">
                    @endforelse
                </div>
            </div>
            <div class="card">
                <h1>{{ $product->name }}</h1>
                <div class="price">{{ currencyFormat($product->price) }}</div>
                <p class="muted">{{ $product->description }}</p>
                <form method="post" action="{{ url('/store/'.$merchant->username.'/cart/add') }}" class="row">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label>Quantity</label>
                    <input type="number" name="quantity" min="1" value="1">
                    <button type="submit">Add to Cart</button>
                    <a href="{{ url('/store/'.$merchant->username.'/cart') }}" style="margin-left:8px">View Cart</a>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function(){
            const main = document.getElementById('mainImage');
            const thumbs = document.getElementById('thumbs');
            if(!thumbs) return;
            thumbs.addEventListener('click', function(e){
                const t = e.target;
                if(t && t.tagName === 'IMG'){
                    [...thumbs.querySelectorAll('img')].forEach(i=>i.classList.remove('active'));
                    t.classList.add('active');
                    main.src = t.getAttribute('data-src');
                }
            });
            const first = thumbs.querySelector('img');
            if(first) first.classList.add('active');
        })();
    </script>
</body>
</html>


