@extends('admin.layouts.app')

@section('title', 'Customer')
@section('breadcrumb', 'Home / Customer / List')
@section('header', 'Manage Customer')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Tambah Customer</a>
                
                <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 rounded-lg {{ $status === '' ? 'bg-slate-100 font-medium' : 'hover:bg-slate-50' }}">All</a>
                <a href="{{ route('admin.customers.index', ['status' => 'pending']) }}" class="px-3 py-2 rounded-lg {{ $status === 'pending' ? 'bg-amber-100 text-amber-800 font-medium' : 'hover:bg-slate-50' }}">Pending</a>
                <a href="{{ route('admin.customers.index', ['status' => 'active']) }}" class="px-3 py-2 rounded-lg {{ $status === 'active' ? 'bg-emerald-100 text-emerald-800 font-medium' : 'hover:bg-slate-50' }}">Active</a>
            </div>

            <form method="get" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <input name="q" value="{{ $q }}" placeholder="Search..." class="w-64 max-w-full rounded-lg border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                <button class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b">
                        <th class="py-3 pr-4">ID</th>
                        <th class="py-3 pr-4">Nama</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Alamat</th>
                        <th class="py-3 pr-4">Kota</th>
                        <th class="py-3 pr-4">No HP</th>
                        <th class="py-3 pr-4">Sales</th>
                        <th class="py-3 pr-4">Email</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-b align-top">
                        <td class="py-3 pr-4 font-medium">{{ $customer->customer_code ?? '-' }}</td>
                        <td class="py-3 pr-4">{{ $customer->full_name }}</td>
                        <td class="py-3 pr-4">
                            @if($customer->status === 'pending')
                                <span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700 font-medium">Pending</span>
                            @elseif($customer->status === 'active')
                                <span class="px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700 font-medium">Active</span>
                            @elseif($customer->status === 'rejected')
                                <span class="px-2 py-1 rounded-full text-xs bg-rose-100 text-rose-700 font-medium">Rejected</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">{{ $customer->status }}</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">{{ Str::limit($customer->address, 30) }}</td>
                        <td class="py-3 pr-4">{{ $customer->city ?? '-' }}</td>
                        <td class="py-3 pr-4">{{ $customer->phone }}</td>
                        <td class="py-3 pr-4">
                            @if($customer->sales)
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700 font-medium">{{ $customer->sales->name }}</span>
                            @else
                                <span class="text-xs text-slate-400 italic">Mandiri</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">{{ $customer->email }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                @if($customer->status === 'pending')
                                    <form method="post" action="{{ route('admin.customers.approve', $customer) }}" onsubmit="return confirm('Setujui pendaftaran ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 text-xs font-medium">Approve</button>
                                    </form>
                                    <form method="post" action="{{ route('admin.customers.reject', $customer) }}" onsubmit="return confirm('Tolak pendaftaran ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-medium">Reject</button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="text-sky-600 hover:text-sky-800"><i class="bi bi-pencil-square"></i> Edit</a>
                                
                                <form method="post" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Hapus customer ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-slate-500">Tidak ada data.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-500">
                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries
            </div>
            <div>{{ $customers->links() }}</div>
        </div>
    </div>
@endsection

