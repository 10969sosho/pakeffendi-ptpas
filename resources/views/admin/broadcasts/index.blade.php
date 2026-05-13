@extends('admin.layouts.app')

@section('title', 'Broadcast')
@section('breadcrumb', 'Home / Broadcast / List')
@section('header', 'Broadcast')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <a href="{{ route('admin.broadcasts.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Tambah Broadcast</a>

            <form method="get" class="flex items-center gap-2">
                <input name="q" value="{{ $q }}" placeholder="Search..." class="w-64 max-w-full rounded-lg border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                <button class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b">
                        <th class="py-3 pr-4">#</th>
                        <th class="py-3 pr-4">Image</th>
                        <th class="py-3 pr-4">Deskripsi</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($broadcasts as $i => $broadcast)
                    <tr class="border-b align-top">
                        <td class="py-3 pr-4">{{ $broadcasts->firstItem() + $i }}</td>
                        <td class="py-3 pr-4">
                            <img src="{{ asset('storage/'.$broadcast->image_path) }}" class="w-16 h-16 rounded-lg object-cover" alt="">
                        </td>
                        <td class="py-3 pr-4">{{ $broadcast->description }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.broadcasts.edit', $broadcast) }}" class="px-3 py-1.5 rounded-lg bg-sky-50 border border-sky-200 text-sky-700 hover:bg-sky-100">Edit</a>
                                <form method="post" action="{{ route('admin.broadcasts.destroy', $broadcast) }}" onsubmit="return confirm('Hapus broadcast ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-slate-500">No data available in table</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-500">
                Showing {{ $broadcasts->firstItem() ?? 0 }} to {{ $broadcasts->lastItem() ?? 0 }} of {{ $broadcasts->total() }} entries
            </div>
            <div>{{ $broadcasts->links() }}</div>
        </div>
    </div>
@endsection

