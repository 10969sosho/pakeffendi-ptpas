@extends('guest.layouts.app')

@section('title', 'Hubungi Kami - PAS Market')

@section('content')
<!-- Page Header -->
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hubungi Kami</li>
            </ol>
        </nav>
        
        <h1 class="h3 fw-bold text-secondary mt-3 mb-0">Hubungi Kami</h1>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Informasi Kontak</h4>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-geo-alt text-primary fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-semibold mb-1">Alamat Kantor</h6>
                                    <p class="text-muted mb-0">
                                        Gedung BCA Tower, Lantai 15<br>
                                        Jl. MH Thamrin No. 1<br>
                                        Jakarta Pusat, DKI Jakarta 10310
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-telephone text-primary fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-semibold mb-1">Telepon</h6>
                                    <p class="text-muted mb-0">
                                        +62 21 1234 5678<br>
                                        <small>Senin - Jumat, 08:00 - 17:00</small>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-envelope text-primary fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-semibold mb-1">Email</h6>
                                    <p class="text-muted mb-0">
                                        support@pasmarket.com<br>
                                        <small>Respon dalam 24 jam</small>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock text-primary fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-semibold mb-1">Jam Operasional</h6>
                                    <p class="text-muted mb-0">
                                        Senin - Jumat: 08:00 - 17:00<br>
                                        Sabtu: 08:00 - 15:00<br>
                                        Minggu: Tutup
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div>
                            <h6 class="fw-semibold mb-3">Ikuti Kami</h6>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm social-share" data-platform="facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm social-share" data-platform="twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm social-share" data-platform="whatsapp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm social-share" data-platform="instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Kirim Pesan</h4>
                        
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contactName" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="contactName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contactEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contactEmail" required>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contactPhone" class="form-label">Nomor HP</label>
                                    <input type="tel" class="form-control" id="contactPhone">
                                </div>
                                <div class="col-md-6">
                                    <label for="contactSubject" class="form-label">Subjek</label>
                                    <select class="form-select" id="contactSubject" required>
                                        <option value="">Pilih Subjek</option>
                                        <option value="general">Pertanyaan Umum</option>
                                        <option value="order">Pesanan</option>
                                        <option value="payment">Pembayaran</option>
                                        <option value="shipping">Pengiriman</option>
                                        <option value="return">Pengembalian</option>
                                        <option value="complaint">Keluhan</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contactMessage" class="form-label">Pesan</label>
                                <textarea class="form-control" id="contactMessage" rows="5" required 
                                          placeholder="Tuliskan pesan Anda di sini..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="contactConsent" required>
                                    <label class="form-check-label" for="contactConsent">
                                        Saya menyetujui <a href="#privacy" class="text-decoration-none">kebijakan privasi</a> 
                                        dan bersedia dihubungi terkait pesan ini.
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="fw-bold text-center text-secondary mb-4">Pertanyaan yang Sering Diajukan</h3>
                
                <div class="accordion" id="faqAccordion">
                    @foreach([
                        ['q' => 'Bagaimana cara memesan produk?', 'a' => 'Anda dapat memesan produk dengan mudah melalui website kami. Cari produk yang Anda inginkan, tambahkan ke keranjang, lalu lakukan checkout dan pembayaran.'],
                        ['q' => 'Apakah produk di PAS Market original?', 'a' => 'Ya, semua produk yang kami jual adalah 100% original dan bergaransi resmi dari brand terkait.'],
                        ['q' => 'Berapa lama waktu pengiriman?', 'a' => 'Waktu pengiriman bervariasi tergantung lokasi Anda. Untuk area Jabodetabek umumnya 1-2 hari kerja, untuk luar Jabodetabek 2-5 hari kerja.'],
                        ['q' => 'Bagaimana cara melacak pesanan saya?', 'a' => 'Anda dapat melacak pesanan melalui halaman "Pesanan Saya" di akun Anda atau menggunakan nomor resi yang kami kirimkan via email.'],
                        ['q' => 'Apakah bisa melakukan pengembalian produk?', 'a' => 'Ya, kami menerima pengembalian produk dalam kondisi tertentu. Silakan lihat kebijakan pengembalian kami untuk informasi lebih lanjut.'],
                        ['q' => 'Bagaimana jika produk yang saya terima rusak?', 'a' => 'Kami akan mengganti produk yang rusak dengan yang baru. Silakan hubungi customer service kami untuk proses klaim garansi.']
                    ] as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Additional Help -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-headset text-primary display-1 mb-3"></i>
                        <h4 class="fw-bold mb-3">Butuh Bantuan Lebih?</h4>
                        <p class="text-muted mb-4">
                            Tim customer service kami siap membantu Anda. Hubungi kami melalui:
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="bi bi-whatsapp text-success fs-2 mb-2"></i>
                                    <h6 class="fw-semibold">WhatsApp</h6>
                                    <p class="text-muted small">+62 812-3456-7890</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="bi bi-telephone text-primary fs-2 mb-2"></i>
                                    <h6 class="fw-semibold">Telepon</h6>
                                    <p class="text-muted small">+62 21 1234 5678</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="bi bi-envelope text-info fs-2 mb-2"></i>
                                    <h6 class="fw-semibold">Email</h6>
                                    <p class="text-muted small">support@pasmarket.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="https://wa.me/6281234567890" class="btn btn-success me-2">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="tel:+622112345678" class="btn btn-primary">
                                <i class="bi bi-telephone me-2"></i>Telepon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contact form submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Validate form
            if (!validateContactForm(data)) {
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mengirim...';
            submitBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Show success message
                PAS.Notification.show('Pesan Anda berhasil dikirim! Kami akan segera merespons.', 'success');
                
                // Reset form
                this.reset();
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 2000);
        });
    }
    
    // FAQ accordion enhancement
    const accordionButtons = document.querySelectorAll('.accordion-button');
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Close other accordions
            accordionButtons.forEach(otherButton => {
                if (otherButton !== this) {
                    otherButton.classList.add('collapsed');
                }
            });
        });
    });
    
    // Social media links
    document.querySelectorAll('.social-share').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.dataset.platform;
            const url = window.location.href;
            const text = 'Hubungi tim support PAS Market';
            
            switch(platform) {
                case 'facebook':
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
                    break;
                case 'twitter':
                    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank');
                    break;
                case 'whatsapp':
                    window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`, '_blank');
                    break;
                case 'instagram':
                    PAS.Notification.show('Buka aplikasi Instagram untuk membagikan', 'info');
                    break;
            }
        });
    });
    
    // Phone number validation
    const phoneInput = document.getElementById('contactPhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Format phone number
            let value = this.value.replace(/[^\d]/g, '');
            if (value.startsWith('0')) {
                value = '+62' + value.substring(1);
            } else if (value.startsWith('62')) {
                value = '+' + value;
            } else if (value && !value.startsWith('+')) {
                value = '+62' + value;
            }
            this.value = value;
        });
    }
});

function validateContactForm(data) {
    // Name validation
    if (!data.contactName || data.contactName.trim().length < 2) {
        PAS.Notification.show('Nama harus diisi minimal 2 karakter', 'warning');
        document.getElementById('contactName').focus();
        return false;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!data.contactEmail || !emailRegex.test(data.contactEmail)) {
        PAS.Notification.show('Email tidak valid', 'warning');
        document.getElementById('contactEmail').focus();
        return false;
    }
    
    // Phone validation (optional)
    if (data.contactPhone && data.contactPhone.trim() !== '') {
        const phoneRegex = /^\+62\d{8,}$/;
        if (!phoneRegex.test(data.contactPhone)) {
            PAS.Notification.show('Nomor HP tidak valid. Gunakan format +62...', 'warning');
            document.getElementById('contactPhone').focus();
            return false;
        }
    }
    
    // Subject validation
    if (!data.contactSubject) {
        PAS.Notification.show('Pilih subjek pesan', 'warning');
        document.getElementById('contactSubject').focus();
        return false;
    }
    
    // Message validation
    if (!data.contactMessage || data.contactMessage.trim().length < 10) {
        PAS.Notification.show('Pesan harus diisi minimal 10 karakter', 'warning');
        document.getElementById('contactMessage').focus();
        return false;
    }
    
    // Consent validation
    if (!data.contactConsent) {
        PAS.Notification.show('Anda harus menyetujui kebijakan privasi', 'warning');
        document.getElementById('contactConsent').focus();
        return false;
    }
    
    return true;
}
</script>
@endpush