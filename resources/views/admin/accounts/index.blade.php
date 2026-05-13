@extends('admin.layouts.app')

@section('title', 'Account')
@section('breadcrumb', 'Home / Account / List')
@section('header', 'Manage Accounts')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <a href="{{ route('admin.accounts.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Tambah User</a>

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
                        <th class="py-3 pr-4">Nama</th>
                        <th class="py-3 pr-4">Email</th>
                        <th class="py-3 pr-4">Role</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($accounts as $i => $account)
                    <tr class="border-b">
                        <td class="py-3 pr-4">{{ $accounts->firstItem() + $i }}</td>
                        <td class="py-3 pr-4">{{ $account->name }}</td>
                        <td class="py-3 pr-4">{{ $account->email }}</td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-1 rounded-lg bg-slate-100 border border-slate-200">{{ $account->role }}</span>
                        </td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.accounts.edit', $account) }}" class="px-3 py-1.5 rounded-lg bg-sky-50 border border-sky-200 text-sky-700 hover:bg-sky-100">Edit</a>
                                <form method="post" action="{{ route('admin.accounts.destroy', $account) }}" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100">Hapus</button>
                                </form>
                            </div>
                        </td>
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
                Showing {{ $accounts->firstItem() ?? 0 }} to {{ $accounts->lastItem() ?? 0 }} of {{ $accounts->total() }} entries
            </div>
            <div>{{ $accounts->links() }}</div>
        </div>
    </div>
@endsection

