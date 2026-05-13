@extends('guest.layouts.app')

@section('title', 'Profil Saya - PAS Market')

@section('content')
<!-- Page Header -->
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>
        
        <h1 class="h3 fw-bold text-secondary mt-3 mb-0">Profil Saya</h1>
    </div>
</section>

<!-- Profile Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Profile Sidebar -->
            @include('guest.partials.profile-sidebar')
            
            <!-- Profile Main Content -->
            <div class="col-lg-9">
                <!-- Profile Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="fw-bold mb-0">Informasi Pribadi</h5>
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('guest.profile.update') }}" novalidate>
                            @csrf
                            
                            @if(isset($is_sales) && $is_sales)
                                <!-- Sales Profile Form -->
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $customer->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $customer->email) }}" required>
                                </div>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>Akun Sales. Informasi detail lainnya dikelola oleh Admin.
                                </div>
                            @else
                                <!-- Customer Profile Form -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="fullName" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="fullName" name="full_name" value="{{ old('full_name', $customer->full_name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" value="{{ $customer->email }}" disabled>
                                    </div>
                                </div>
    
                                <div class="row g-3 mt-0">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Nomor HP</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
                
                @if(isset($is_sales) && $is_sales)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0">Riwayat Order</h5>
                                <a href="{{ route('guest.orders.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i>Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="get" action="{{ route('guest.profile.index') }}" class="row g-2 align-items-end mb-3" data-ajax="false">
                                <div class="col-12 col-md-4">
                                    <label class="form-label mb-1">Nama Customer</label>
                                    <input type="text" name="customer" value="{{ $order_filters['customer'] ?? '' }}" class="form-control form-control-sm" placeholder="Contoh: Budi">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label mb-1">Dari</label>
                                    <input type="date" name="date_from" value="{{ $order_filters['date_from'] ?? '' }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label mb-1">Sampai</label>
                                    <input type="date" name="date_to" value="{{ $order_filters['date_to'] ?? '' }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label mb-1">Search</label>
                                    <input type="text" name="q" value="{{ $order_filters['q'] ?? '' }}" class="form-control form-control-sm" placeholder="No order / barang">
                                </div>
                                <div class="col-6 col-md-1 d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                </div>
                                <div class="col-6 col-md-1 d-grid">
                                    <a href="{{ route('guest.profile.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </a>
                                </div>
                            </form>

                            <div class="row g-2 mb-3">
                                <div class="col-12 col-md-4">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">Periode</div>
                                        <div class="fw-semibold">
                                            {{ ($order_filters['date_from'] ?? '-') }} s/d {{ ($order_filters['date_to'] ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">Total Nominal</div>
                                        <div class="fw-semibold">Rp {{ number_format((float) ($order_stats['total_nominal'] ?? 0), 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">Total Transaksi</div>
                                        <div class="fw-semibold">{{ (int) ($order_stats['total_transaksi'] ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No. Pesanan</th>
                                            <th>Nama Customer</th>
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
                                                <td>{{ $order->customer?->full_name ?? '-' }}</td>
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
                                                <td colspan="6" class="text-muted text-center py-4">Tidak ada order untuk filter/periode ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(isset($orders) && method_exists($orders, 'links'))
                                <div class="mt-3 d-flex justify-content-center">
                                    {{ $orders->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
                            <a href="{{ route('guest.orders.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Lihat Semua
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No. Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($recentOrders ?? collect()) as $order)
                                            <tr>
                                                <td class="fw-semibold">{{ $order->order_no }}</td>
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
                                                <td colspan="5" class="text-muted text-center py-4">Belum ada pesanan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
