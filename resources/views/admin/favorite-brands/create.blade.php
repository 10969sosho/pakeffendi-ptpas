@extends('admin.layouts.app')

@section('title', 'Create Favorite Brand')
@section('breadcrumb', 'Home / Favorite Brand / Create')
@section('header', 'Tambah Brand Terfavorit')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm w-full">
        <form method="post" action="{{ route('admin.favorite-brands.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Nama Brand *</label>
                <select name="product_brand_ids[]" multiple required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500 h-56">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->brand_code }}">{{ $brand->brand_name }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-slate-500 mt-1">Bisa pilih lebih dari satu (Ctrl/Cmd + klik).</div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
                <a href="{{ route('admin.favorite-brands.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
@endsection
