<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index(Product $product)
    {
        return view('admin.products.images', [
            'product' => $product->load(['images', 'variantItems.variant1', 'variantItems.variant2']),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:4096'],
            'variant_item_id' => ['nullable', 'integer'],
        ]);

        $variantItemId = $validated['variant_item_id'] ?? null;
        if ($variantItemId) {
            $exists = $product->variantItems()->where('id', $variantItemId)->exists();
            if (! $exists) {
                return back()->withErrors(['variant_item_id' => 'Variant tidak valid untuk produk ini.']);
            }
        }

        $query = $product->images();
        if ($variantItemId) {
            $query = $query->where('product_variant_item_id', $variantItemId);
        }
        $nextOrder = (int) ($query->max('sort_order') ?? 0);

        foreach ($validated['images'] as $file) {
            $nextOrder++;
            $path = $file->store('product-images', 'public');
            $product->images()->create([
                'product_variant_item_id' => $variantItemId,
                'image_path' => $path,
                'sort_order' => $nextOrder,
            ]);
        }

        ActivityLogger::log('created', 'ProductImage - '.$product->sku);

        return back()->with('status', 'Gambar berhasil ditambahkan.');
    }

    public function destroy(ProductImage $productImage)
    {
        $sku = $productImage->product?->sku;
        $productImage->delete();

        ActivityLogger::log('deleted', 'ProductImage - '.($sku ?: ''));

        return back()->with('status', 'Gambar berhasil dihapus.');
    }
}
