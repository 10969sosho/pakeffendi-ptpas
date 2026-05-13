<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class GuestProductApiController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'string', 'max:50'],
            'brand_id' => ['nullable', 'string', 'max:50'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 20);

        $query = Product::query()
            ->with(['brand:brand_code,brand_name', 'category:category_code,name'])
            ->where('discontinued', false)
            ->orderByDesc('created_at');

        if (! empty($validated['q'])) {
            $q = trim((string) $validated['q']);
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if (! empty($validated['category_id'])) {
            $query->where('product_category_code', $validated['category_id']);
        }

        if (! empty($validated['brand_id'])) {
            $query->where('product_brand_code', $validated['brand_id']);
        }

        $products = $query->paginate($perPage);

        $products->getCollection()->transform(function (Product $p) {
            return [
                'id' => $p->id,
                'sku' => $p->sku,
                'name' => $p->name,
                'variant' => $p->variant,
                'brand' => $p->brand?->brand_name,
                'category' => $p->category?->name,
                'image_path' => $p->photo_path,
                'price_1' => (float) $p->price_1,
                'updated_at' => $p->updated_at?->toISOString(),
            ];
        });

        return response()->json($products);
    }

    public function show(Product $product)
    {
        if ($product->discontinued) {
            abort(404);
        }

        $product->load([
            'brand:brand_code,brand_name',
            'category:category_code,name',
        ]);

        return response()->json([
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'variant' => $product->variant,
            'description' => $product->description,
            'unit' => $product->unit,
            'weight_kg' => (float) $product->weight_kg,
            'brand' => $product->brand?->brand_name,
            'category' => $product->category?->name,
            'photo_path' => $product->photo_path,
            'image_path' => $product->photo_path,
            'price_tiers' => [
                [
                    'min_qty' => 1,
                    'max_qty' => $product->qty_1 ? ((int) $product->qty_1 - 1) : null,
                    'price' => (float) $product->price_1,
                    'discount_percent' => (float) ($product->disc_1 ?? 0),
                ],
                [
                    'min_qty' => $product->qty_1 ? (int) $product->qty_1 : null,
                    'max_qty' => $product->qty_2 ? ((int) $product->qty_2 - 1) : null,
                    'price' => (float) ($product->price_2 ?? $product->price_1),
                    'discount_percent' => (float) ($product->disc_2 ?? 0),
                ],
                [
                    'min_qty' => $product->qty_2 ? (int) $product->qty_2 : null,
                    'max_qty' => $product->qty_3 ? ((int) $product->qty_3 - 1) : null,
                    'price' => (float) ($product->price_3 ?? $product->price_2 ?? $product->price_1),
                    'discount_percent' => (float) ($product->disc_3 ?? 0),
                ],
                [
                    'min_qty' => $product->qty_3 ? (int) $product->qty_3 : null,
                    'max_qty' => null,
                    'price' => (float) ($product->price_3 ?? $product->price_2 ?? $product->price_1),
                    'discount_percent' => (float) ($product->disc_3 ?? 0),
                ],
            ],
            'updated_at' => $product->updated_at?->toISOString(),
        ]);
    }
}
