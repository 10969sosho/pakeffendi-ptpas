<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductBrandController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $brands = ProductBrand::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query
                        ->where('brand_code', 'like', "%{$q}%")
                        ->orWhere('brand_name', 'like', "%{$q}%");
                });
            })
            ->orderBy('brand_name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.brands.index', [
            'brands' => $brands,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:255', 'unique:product_brands,brand_name'],
            'brand_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;
        if ($request->hasFile('brand_image')) {
            $imagePath = $request->file('brand_image')->store('brands', 'public');
        }

        $brand = ProductBrand::create([
            'brand_code' => $this->generateBrandCode(),
            'brand_name' => $validated['brand_name'],
            'brand_image_path' => $imagePath,
        ]);

        ActivityLogger::log('created', 'ProductBrand - '.$brand->brand_code);

        return redirect()->route('admin.brands.index')->with('status', 'Brand berhasil dibuat.');
    }

    public function edit(ProductBrand $brand)
    {
        return view('admin.brands.edit', [
            'brand' => $brand,
        ]);
    }

    public function update(Request $request, ProductBrand $brand)
    {
        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:255', Rule::unique('product_brands', 'brand_name')->ignore($brand->brand_code, 'brand_code')],
            'brand_image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('brand_image')) {
            $brand->brand_image_path = $request->file('brand_image')->store('brands', 'public');
        }

        $brand->brand_name = $validated['brand_name'];
        $brand->save();

        ActivityLogger::log('updated', 'ProductBrand - '.$brand->brand_code);

        return redirect()->route('admin.brands.index')->with('status', 'Brand berhasil diupdate.');
    }

    public function destroy(ProductBrand $brand)
    {
        $code = $brand->brand_code;
        $brand->delete();

        ActivityLogger::log('deleted', 'ProductBrand - '.$code);

        return redirect()->route('admin.brands.index')->with('status', 'Brand berhasil dihapus.');
    }

    private function generateBrandCode(): string
    {
        do {
            $code = 'B'.strtoupper(Str::random(6));
        } while (ProductBrand::query()->where('brand_code', $code)->exists());

        return $code;
    }
}
