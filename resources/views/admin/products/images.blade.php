@extends('admin.layouts.app')

@section('title', 'Product Images')
@section('breadcrumb', 'Home / Stock / Images')
@section('header', 'Product Images')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div>
                <div class="text-sm text-slate-500">SKU</div>
                <div class="font-semibold">{{ $product->sku }} - {{ $product->name }}</div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Kembali</a>
        </div>

        <form method="post" action="{{ route('admin.products.images.store', $product) }}" enctype="multipart/form-data" class="flex flex-col md:flex-row md:items-end gap-3 mb-6">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Tambah Images</label>
                <input type="file" name="images[]" accept="image/*" multiple required class="w-full">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Untuk Variant</label>
                <select name="variant_item_id" id="variantSelect" class="w-56 rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Umum (tanpa variant)</option>
                    @foreach ($product->variantItems as $item)
                        @php
                            $label = $item->variant2
                                ? ($item->variant1?->name.': '.$item->variant1?->value.' / '.$item->variant2?->name.': '.$item->variant2?->value)
                                : ($item->variant1?->name.': '.$item->variant1?->value);
                        @endphp
                        <option value="{{ $item->id }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Upload</button>
        </form>

        <div class="flex items-center justify-between mb-2">
            <div class="text-sm text-slate-600">Filter gambar</div>
            <select id="filterVariant" class="w-56 rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                <option value="">Semua</option>
                <option value="none">Umum (tanpa variant)</option>
                @foreach ($product->variantItems as $item)
                    @php
                        $label = $item->variant2
                            ? ($item->variant1?->name.': '.$item->variant1?->value.' / '.$item->variant2?->name.': '.$item->variant2?->value)
                            : ($item->variant1?->name.': '.$item->variant1?->value);
                    @endphp
                    <option value="{{ $item->id }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-3" id="imageGrid">
            @forelse ($product->images->sortBy('sort_order') as $image)
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white" data-variant="{{ $image->product_variant_item_id ?: 'none' }}">
                    <img src="{{ $image->image_url }}" class="w-full h-24 object-cover" alt="">
                    <div class="p-2 flex items-center justify-between">
                        <div class="text-xs text-slate-500">#{{ $image->sort_order }}</div>
                        <form method="post" action="{{ route('admin.products.images.destroy', $image) }}" onsubmit="return confirm('Hapus gambar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-700">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-2 md:col-span-6 text-center text-slate-500 py-8">Belum ada gambar.</div>
            @endforelse
        </div>
    </div>
    <script>
        (function () {
            const filter = document.getElementById('filterVariant');
            const grid = document.getElementById('imageGrid');
            filter.addEventListener('change', function () {
                const val = filter.value;
                grid.querySelectorAll('[data-variant]').forEach(function (el) {
                    if (val === '' || el.getAttribute('data-variant') === val) {
                        el.style.display = '';
                    } else {
                        el.style.display = 'none';
                    }
                });
            });
        })();
    </script>
@endsection
