@extends('admin.layouts.app')

@section('title', 'Sales Order')
@section('breadcrumb', 'Home / Sales Order / List')
@section('header', 'Manage Sales Order')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div class="text-sm text-slate-500">List</div>

            <form method="get" class="flex items-center gap-2">
                <input name="q" value="{{ $q }}" placeholder="Search..." class="w-64 max-w-full rounded-lg border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                <button class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b">
                        <th class="py-3 pr-4">ID</th>
                        <th class="py-3 pr-4">Customer</th>
                        <th class="py-3 pr-4">Payment Type</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Total</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b">
                        <td class="py-3 pr-4 font-medium">{{ $order->order_no }}</td>
                        <td class="py-3 pr-4">{{ $order->customer?->customer_code }} - {{ $order->customer?->full_name }}</td>
                        <td class="py-3 pr-4">{{ $order->payment_type }}</td>
                        <td class="py-3 pr-4">{{ $order->status }}</td>
                        <td class="py-3 pr-4">{{ number_format((float) $order->grand_total, 2) }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.sales-orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100">Detail</a>
                                <form method="post" action="{{ route('admin.sales-orders.destroy', $order) }}" onsubmit="return confirm('Hapus sales order ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada data.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-500">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
            </div>
            <div>{{ $orders->links() }}</div>
        </div>
    </div>
@endsection

