@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('breadcrumb', 'Home / Customer / Edit')
@section('header', 'Edit Customer')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm w-full">
        <form method="post" action="{{ route('admin.customers.update', $customer) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Customer ID</label>
                    <input value="{{ $customer->customer_code }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50">
                </div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-3">Informasi Akun & Identitas</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap *</label>
                        <input name="full_name" value="{{ old('full_name', $customer->full_name) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Jenis Akun *</label>
                        <select name="account_type" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                            <option value="personal" @selected(old('account_type', $customer->account_type) === 'personal')>Personal</option>
                            <option value="company" @selected(old('account_type', $customer->account_type) === 'company')>Company</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No KTP *</label>
                        <input name="ktp_number" value="{{ old('ktp_number', $customer->ktp_number) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">NPWP</label>
                        <input name="npwp" value="{{ old('npwp', $customer->npwp) }}" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-3">Informasi Login</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Password (opsional)</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-3">Informasi Alamat</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Alamat</label>
                        <textarea name="address" rows="3" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">{{ old('address', $customer->address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Provinsi</label>
                        <input name="province" value="{{ old('province', $customer->province) }}" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Kota</label>
                        <input name="city" value="{{ old('city', $customer->city) }}" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kode POS</label>
                        <input name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-3">Informasi Kontak & Perusahaan</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">No HP *</label>
                        <input name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Contact Person *</label>
                        <input name="contact_person" value="{{ old('contact_person', $customer->contact_person) }}" required class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Company Name</label>
                        <input name="company_name" value="{{ old('company_name', $customer->company_name) }}" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Internal Code</label>
                        <input name="internal_code" value="{{ old('internal_code', $customer->internal_code) }}" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Sales Person (Optional)</label>
                        <select name="sales_id" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                            <option value="">-- Tanpa Sales --</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" @selected(old('sales_id', $customer->sales_id) == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Ganti sales yang menangani customer ini.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
@endsection
