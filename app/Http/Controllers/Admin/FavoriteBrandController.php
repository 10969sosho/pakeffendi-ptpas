<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoriteBrand;
use App\Models\ProductBrand;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class FavoriteBrandController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $favorites = FavoriteBrand::query()
            ->with('brand')
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('brand', fn ($q2) => $q2->where('brand_name', 'like', "%{$q}%"));
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.favorite-brands.index', [
            'favorites' => $favorites,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('admin.favorite-brands.create', [
            'brands' => ProductBrand::query()->orderBy('brand_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_brand_ids' => ['required', 'array', 'min:1'],
            'product_brand_ids.*' => ['required', 'string', 'max:50', 'exists:product_brands,brand_code'],
        ]);

        foreach ($validated['product_brand_ids'] as $brandId) {
            FavoriteBrand::firstOrCreate([
                'product_brand_code' => $brandId,
            ]);
        }

        ActivityLogger::log('created', 'FavoriteBrand - added');

        return redirect()->route('admin.favorite-brands.index')->with('status', 'Favorite brand berhasil disimpan.');
    }

    public function destroy(FavoriteBrand $favoriteBrand)
    {
        $name = $favoriteBrand->brand?->brand_name;
        $favoriteBrand->delete();

        ActivityLogger::log('deleted', 'FavoriteBrand - '.($name ?: ''));

        return redirect()->route('admin.favorite-brands.index')->with('status', 'Favorite brand berhasil dihapus.');
    }
}
