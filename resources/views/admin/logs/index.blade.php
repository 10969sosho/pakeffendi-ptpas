@extends('admin.layouts.app')

@section('title', 'Logbook')
@section('breadcrumb', 'Home / Advance / Logbook')
@section('header', 'Activity Log')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div class="text-sm text-slate-500">Read-only</div>

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
                        <th class="py-3 pr-4">Description</th>
                        <th class="py-3 pr-4">Data</th>
                        <th class="py-3 pr-4">Actor</th>
                        <th class="py-3 pr-4">Created At</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($logs as $i => $log)
                    <tr class="border-b align-top">
                        <td class="py-3 pr-4">{{ $logs->firstItem() + $i }}</td>
                        <td class="py-3 pr-4">{{ $log->description }}</td>
                        <td class="py-3 pr-4">{{ $log->data }}</td>
                        <td class="py-3 pr-4">{{ $log->actor?->name ?? '-' }}</td>
                        <td class="py-3 pr-4">{{ $log->created_at?->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500">Tidak ada data.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-500">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
            </div>
            <div>{{ $logs->links() }}</div>
        </div>
    </div>
@endsection

