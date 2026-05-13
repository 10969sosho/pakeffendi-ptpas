@extends('admin.layouts.app')

@section('title', 'Create Broadcast')
@section('breadcrumb', 'Home / Broadcast / Create')
@section('header', 'Create Broadcast')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm w-full">
        <form method="post" action="{{ route('admin.broadcasts.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Broadcast Image *</label>
                <input type="file" name="image" accept="image/*" required class="w-full">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description *</label>
                <textarea name="description" rows="4" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
                <a href="{{ route('admin.broadcasts.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
@endsection
