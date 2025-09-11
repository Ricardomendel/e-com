<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank You - {{ $merchant->merchantSetting->store_name ?? $merchant->name }}</title>
    <style>
        body{font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;margin:0;padding:40px;text-align:center}
        a{display:inline-block;margin-top:16px}
    </style>
</head>
<body>
    <h2>Thank you for your order!</h2>
    <p>We will contact you shortly to confirm the delivery.</p>
    <a href="{{ url('/store/'.$merchant->username) }}">Back to store</a>
</body>
</html>


