<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductImageDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure directory exists
        Storage::makeDirectory('public/product-images');

        $source = public_path('images/admin-panel.png');
        if (!file_exists($source)) {
            // fallback to API image if available
            $alt = public_path('images/LaraCommerce-API.png');
            if (file_exists($alt)) $source = $alt;
        }

        foreach (Product::limit(16)->get() as $product) {
            $filename = 'product_' . $product->id . '.png';
            $dest = 'public/product-images/' . $filename;

            if (file_exists($source) && !Storage::exists($dest)) {
                Storage::put($dest, file_get_contents($source));
            }

            ProductImage::firstOrCreate([
                'product_id' => $product->id,
                'slug' => $filename,
            ], [
                'name' => $filename,
            ]);
        }
    }
}


