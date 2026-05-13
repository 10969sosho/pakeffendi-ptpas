@extends('guest.layouts.app')

@section('title', 'Log Aktivitas - PAS Market')

@section('content')
<!-- Page Header -->
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
            </ol>
        </nav>
        
        <h1 class="h3 fw-bold text-secondary mt-3 mb-0">Log Aktivitas</h1>
    </div>
</section>

<!-- Logs Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Profile Sidebar -->
            @include('guest.partials.profile-sidebar')
            
            <!-- Logs Main Content -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="fw-bold mb-0">Riwayat Aktivitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Aktivitas</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            @if($log->event == 'created')
                                                <span class="badge bg-success">Created</span>
                                            @elseif($log->event == 'updated')
                                                <span class="badge bg-warning text-dark">Updated</span>
                                            @elseif($log->event == 'deleted')
                                                <span class="badge bg-danger">Deleted</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $log->event ?? 'Log' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->description }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada aktivitas.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
