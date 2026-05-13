@extends('admin.layouts.app')

@section('title', 'Favorite Brand')
@section('breadcrumb', 'Home / Favorite Brand / List')
@section('header', 'Manage Favorite Brands')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <a href="{{ route('admin.favorite-brands.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Tambah Brand Terfavorit</a>

            <form method="get" class="flex items-center gap-2">
                <input name="q" value="{{ $q }}" placeholder="Search..." class="w-64 max-w-full rounded-lg border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                <button class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b">
                        <th class="py-3 pr-4">No</th>
                        <th class="py-3 pr-4">Brand</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($favorites as $i => $fav)
                    <tr class="border-b">
                        <td class="py-3 pr-4">{{ $favorites->firstItem() + $i }}</td>
                        <td class="py-3 pr-4 font-medium">{{ $fav->brand?->brand_name }}</td>
                        <td class="py-3 pr-4">
                            <form method="post" action="{{ route('admin.favorite-brands.destroy', $fav) }}" onsubmit="return confirm('Hapus brand favorit ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-500">Tidak ada data.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-500">
                Showing {{ $favorites->firstItem() ?? 0 }} to {{ $favorites->lastItem() ?? 0 }} of {{ $favorites->total() }} entries
            </div>
            <div>{{ $favorites->links() }}</div>
        </div>
    </div>
@endsection

