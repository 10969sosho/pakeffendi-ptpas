@extends('guest.layouts.app')

@section('title', 'Pesanan Saya - PAS Market')

@section('content')
<!-- Page Header -->
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ isset($is_sales) && $is_sales ? 'Riwayat Order' : 'Pesanan' }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h1 class="h3 fw-bold text-secondary mb-0">{{ isset($is_sales) && $is_sales ? 'Riwayat Order' : 'Pesanan Saya' }}</h1>
        </div>
    </div>
</section>

<!-- Orders Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            @include('guest.partials.profile-sidebar')
            
            <div class="col-lg-9">
                @php
                    $f = $order_filters ?? [];
                    $stats = $order_stats ?? ['total_nominal' => 0, 'total_transaksi' => 0];
                @endphp

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="fw-bold mb-0">{{ isset($is_sales) && $is_sales ? 'Riwayat Order' : 'Pesanan Saya' }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{ route('guest.orders.index') }}" class="row g-2 align-items-end mb-3" data-ajax="false">
                            @if(isset($is_sales) && $is_sales)
                                <div class="col-12 col-md-4">
                                    <label class="form-label mb-1">Nama Customer</label>
                                    <input type="text" name="customer" value="{{ $f['customer'] ?? '' }}" class="form-control form-control-sm" placeholder="Contoh: Budi">
                                </div>
                            @endif
                            <div class="col-6 col-md-2">
                                <label class="form-label mb-1">Dari</label>
                                <input type="date" name="date_from" value="{{ $f['date_from'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label mb-1">Sampai</label>
                                <input type="date" name="date_to" value="{{ $f['date_to'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label mb-1">Search</label>
                                <input type="text" name="q" value="{{ $f['q'] ?? '' }}" class="form-control form-control-sm" placeholder="No order / barang">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label mb-1">Filter Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    @foreach([\App\Models\SalesOrder::STATUS_NEW, \App\Models\SalesOrder::STATUS_ON_PROGRESS, \App\Models\SalesOrder::STATUS_ON_DELIVERY, \App\Models\SalesOrder::STATUS_FINISHED] as $status)
                                        <option value="{{ $status }}" @selected(($f['status'] ?? '') === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <button class="btn btn-primary btn-sm w-100" type="submit">
                                    <i class="bi bi-funnel me-1"></i>Terapkan
                                </button>
                            </div>
                            <div class="col-6 col-md-2">
                                <a href="{{ route('guest.orders.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>
                        </form>

                        <div class="row g-2 mb-3">
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-2 bg-light">
                                    <div class="text-muted small">Periode</div>
                                    <div class="fw-semibold">{{ ($f['date_from'] ?? '-') }} s/d {{ ($f['date_to'] ?? '-') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="border rounded p-2 bg-light">
                                    <div class="text-muted small">Total Nominal</div>
                                    <div class="fw-semibold">Rp {{ number_format((float) ($stats['total_nominal'] ?? 0), 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="border rounded p-2 bg-light">
                                    <div class="text-muted small">Total Transaksi</div>
                                    <div class="fw-semibold">{{ (int) ($stats['total_transaksi'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Pesanan</th>
                                        @if(isset($is_sales) && $is_sales)
                                            <th>Nama Customer</th>
                                        @endif
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($orders ?? collect()) as $order)
                                        <tr>
                                            <td class="fw-semibold">{{ $order->order_no }}</td>
                                            @if(isset($is_sales) && $is_sales)
                                                <td>{{ $order->customer?->full_name ?? '-' }}</td>
                                            @endif
                                            <td>{{ $order->order_date?->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                            </td>
                                            <td class="fw-semibold">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('guest.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ (isset($is_sales) && $is_sales) ? 6 : 5 }}" class="text-muted text-center py-4">Tidak ada order untuk filter/periode ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if(isset($orders) && method_exists($orders, 'links'))
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
