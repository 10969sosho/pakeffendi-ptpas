@extends('admin.layouts.app')

@section('title', 'Edit Sales Order')
@section('breadcrumb', 'Home / Sales Order / Detail / Edit')
@section('header', 'Edit Sales Order')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm w-full">
        <div class="mb-4">
            <div class="text-sm text-slate-500">Sales Order No</div>
            <div class="font-semibold">{{ $order->order_no }}</div>
        </div>

        <form method="post" action="{{ route('admin.sales-orders.update', $order) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Order Status *</label>
                <select name="status" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
                <a href="{{ route('admin.sales-orders.show', $order) }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
@endsection
