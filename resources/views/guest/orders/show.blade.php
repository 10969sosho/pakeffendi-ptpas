@extends('guest.layouts.app')

@section('title', 'Detail Pesanan - PAS Market')

@section('content')
<!-- Page Header -->
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('guest.orders.index') }}">Pesanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h1 class="h3 fw-bold text-secondary mb-0">Detail Pesanan</h1>
        </div>
    </div>
</section>

<!-- Order Detail Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            @include('guest.partials.profile-sidebar')

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0">Detail Pesanan</h5>
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <h6 class="fw-semibold mb-1">No. Pesanan</h6>
                                        <p class="mb-0">{{ $order->order_no }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="fw-semibold mb-1">Tanggal Pesanan</h6>
                                        <p class="mb-0">{{ $order->order_date?->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h6 class="fw-semibold mb-1">Penerima</h6>
                                        <p class="mb-0">{{ $order->delivery_to ?? '-' }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="fw-semibold mb-1">Telepon</h6>
                                        <p class="mb-0">{{ $order->delivery_phone ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h6 class="fw-semibold mb-1">Alamat</h6>
                                    <p class="mb-0">{{ $order->delivery_address ?? '-' }}</p>
                                </div>
                                @if(!empty($order->notes))
                                    <div class="mt-3">
                                        <h6 class="fw-semibold mb-1">Catatan</h6>
                                        <p class="mb-0">{{ $order->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <h5 class="fw-bold mb-0">Produk yang Dibeli</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produk</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-end">Harga</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($order->items ?? collect()) as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <h6 class="fw-semibold mb-1">{{ $item->product_name }}</h6>
                                                                <small class="text-muted">{{ $item->product?->brand?->brand_name ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ (int) $item->quantity }}</td>
                                                    <td class="text-end">
                                                        Rp {{ number_format((float) $item->net_price, 0, ',', '.') }}
                                                        @if(((float) $item->discount_percent) > 0)
                                                            <div class="text-muted small">disc {{ rtrim(rtrim(number_format((float) $item->discount_percent, 2, '.', ''), '0'), '.') }}%</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-end fw-semibold">Rp {{ number_format((float) $item->final_total, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <h5 class="fw-bold mb-0">Ringkasan</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total</span>
                                    <span class="fw-bold">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Ongkir</span>
                                    <span>Rp {{ number_format((float) $order->shipping_fee, 0, ',', '.') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Grand Total</span>
                                    <span class="fw-bold h5 text-primary">Rp {{ number_format((float) ($order->grand_total + $order->shipping_fee), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
