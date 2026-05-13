@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">+{{ number_format($newCustomersCount) }}</div>
                    <div class="text-sm text-slate-500">New Customers</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-100"></div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">+{{ number_format($totalSalesCount) }}</div>
                    <div class="text-sm text-slate-500">Total Sales</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100"></div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-64"></div>
@endsection

