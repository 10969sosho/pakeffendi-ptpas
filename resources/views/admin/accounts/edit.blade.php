@extends('admin.layouts.app')

@section('title', 'Edit Account')
@section('breadcrumb', 'Home / Account / Edit')
@section('header', 'Edit Account')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm w-full">
        <form method="post" action="{{ route('admin.accounts.update', $account) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap *</label>
                <input name="name" value="{{ old('name', $account->name) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $account->email) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Password (opsional)</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Photo</label>
                <input type="file" name="photo" accept="image/*" class="w-full">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Role *</label>
                <select name="role" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    <option value="admin" @selected(old('role', $account->role) === 'admin')>Admin</option>
                    <option value="super admin" @selected(old('role', $account->role) === 'super admin')>Super Admin</option>
                    <option value="sales" @selected(old('role', $account->role) === 'sales')>Sales</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
                <a href="{{ route('admin.accounts.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
@endsection
