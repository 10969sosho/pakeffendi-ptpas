@extends('admin.layouts.app')

@section('title', 'Edit Brand')
@section('breadcrumb', 'Home / Brand / Edit')
@section('header', 'Edit Product Brand')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm w-full">
        <form method="post" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Brand ID</label>
                    <input value="{{ $brand->brand_code }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Brand Image</label>
                <input type="file" name="brand_image" accept="image/*" class="w-full">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Brand Name *</label>
                <input name="brand_name" value="{{ old('brand_name', $brand->brand_name) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
                <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
@endsection
