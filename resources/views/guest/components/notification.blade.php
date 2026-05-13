{{-- Notification Toast Component --}}
@props(['type' => 'info', 'message' => '', 'duration' => 3000])

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="{{ $duration }}">
        <div class="toast-header">
            <i class="bi bi-{{ ['info' => 'info-circle', 'success' => 'check-circle', 'warning' => 'exclamation-triangle', 'danger' => 'x-circle'][$type] }} text-{{ $type }} me-2"></i>
            <strong class="me-auto">Notifikasi</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ $message }}
        </div>
    </div>
</div>