@extends('guest.layouts.app')

@section('title', 'Tentang Kami - PAS Market')

@section('content')
<!-- Page Header -->
<section class="bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
            </ol>
        </nav>
        
        <h1 class="h3 fw-bold text-secondary mt-3 mb-0">Tentang Kami</h1>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <!-- Company Overview -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="fw-bold text-secondary mb-4">PAS Market - Solusi Belanja Online Anda</h2>
                <p class="lead text-muted mb-4">
                    PAS Market adalah platform e-commerce terpercaya yang menghubungkan pelanggan dengan produk berkualitas 
                    dari berbagai brand ternama di Indonesia.
                </p>
                <p class="text-muted mb-4">
                    Didirikan pada tahun 2020, kami berkomitmen untuk memberikan pengalaman belanja online yang aman, 
                    nyaman, dan menyenangkan bagi semua pelanggan kami.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-primary fs-4 me-2"></i>
                            <span>Produk Original</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-primary fs-4 me-2"></i>
                            <span>Harga Kompetitif</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-primary fs-4 me-2"></i>
                            <span>Pengiriman Cepat</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-primary fs-4 me-2"></i>
                            <span>Garansi Resmi</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/f8f9fa/333333?text=About+PAS+Market" 
                     alt="About PAS Market" class="img-fluid rounded-custom shadow-sm">
            </div>
        </div>
        
        <!-- Mission & Vision -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="bi bi-bullseye text-primary display-4"></i>
                        </div>
                        <h4 class="fw-bold text-center mb-3">Misi Kami</h4>
                        <p class="text-muted text-center">
                            Menyediakan platform belanja online yang aman, terpercaya, dan mudah digunakan 
                            untuk memenuhi kebutuhan pelanggan dengan produk berkualitas terbaik.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="bi bi-eye text-primary display-4"></i>
                        </div>
                        <h4 class="fw-bold text-center mb-3">Visi Kami</h4>
                        <p class="text-muted text-center">
                            Menjadi e-commerce terdepan di Indonesia yang memberikan pengalaman belanja 
                            terbaik dan membangun ekosistem digital yang berkelanjutan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="display-4 fw-bold text-primary mb-2">500K+</div>
                    <div class="text-muted">Pelanggan Aktif</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="display-4 fw-bold text-primary mb-2">50K+</div>
                    <div class="text-muted">Produk Tersedia</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="display-4 fw-bold text-primary mb-2">100+</div>
                    <div class="text-muted">Brand Partner</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="display-4 fw-bold text-primary mb-2">99%</div>
                    <div class="text-muted">Customer Satisfaction</div>
                </div>
            </div>
        </div>
        
        <!-- Team -->
        <div class="mb-5">
            <h3 class="fw-bold text-center text-secondary mb-4">Tim Kami</h3>
            <div class="row g-4">
                @foreach([
                    ['name' => 'Budi Santoso', 'role' => 'Founder & CEO', 'image' => 'https://via.placeholder.com/150x150/f8f9fa/333333?text=CEO'],
                    ['name' => 'Siti Nurhaliza', 'role' => 'CTO', 'image' => 'https://via.placeholder.com/150x150/f8f9fa/333333?text=CTO'],
                    ['name' => 'Ahmad Dahlan', 'role' => 'Head of Marketing', 'image' => 'https://via.placeholder.com/150x150/f8f9fa/333333?text=Marketing'],
                    ['name' => 'Dewi Lestari', 'role' => 'Head of Operations', 'image' => 'https://via.placeholder.com/150x150/f8f9fa/333333?text=Operations']
                ] as $member)
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" 
                             class="rounded-circle mb-3" width="120" height="120">
                        <h6 class="fw-bold mb-1">{{ $member['name'] }}</h6>
                        <small class="text-muted">{{ $member['role'] }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Values -->
        <div class="mb-5">
            <h3 class="fw-bold text-center text-secondary mb-4">Nilai-Nilai Kami</h3>
            <div class="row g-4">
                @foreach([
                    ['icon' => 'shield-check', 'title' => 'Kepercayaan', 'desc' => 'Kami menjaga kepercayaan pelanggan dengan integritas dan transparansi'],
                    ['icon' => 'lightning', 'title' => 'Inovasi', 'desc' => 'Kami terus berinovasi untuk memberikan pengalaman terbaik'],
                    ['icon' => 'people', 'title' => 'Kolaborasi', 'desc' => 'Kami bekerja sama untuk mencapai tujuan bersama'],
                    ['icon' => 'heart', 'title' => 'Kepedulian', 'desc' => 'Kami peduli terhadap pelanggan dan komunitas']
                ] as $value)
                <div class="col-md-3">
                    <div class="text-center">
                        <i class="bi bi-{{ $value['icon'] }} text-primary display-5 mb-3"></i>
                        <h5 class="fw-bold mb-2">{{ $value['title'] }}</h5>
                        <p class="text-muted small">{{ $value['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="text-center bg-light rounded-custom p-5">
            <h3 class="fw-bold text-secondary mb-3">Bergabung dengan Komunitas PAS Market</h3>
            <p class="text-muted mb-4">
                Jadilah bagian dari ribuan pelanggan yang puas berbelanja di PAS Market
            </p>
            <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                <a href="#register" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </a>
                <a href="#products" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-shop me-2"></i>Mulai Belanja
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const numbers = entry.target.querySelectorAll('.display-4');
                numbers.forEach(number => {
                    const finalValue = number.textContent;
                    const numericValue = parseInt(finalValue.replace(/[^\d]/g, ''));
                    
                    if (numericValue) {
                        animateNumber(number, 0, numericValue, finalValue.includes('K') ? 'K' : 
                                     finalValue.includes('%') ? '%' : '', 2000);
                    }
                });
            }
        });
    }, observerOptions);
    
    const statsSection = document.querySelector('.row.g-4.mb-5');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    
    // Team member hover effects
    document.querySelectorAll('.col-md-3.col-6').forEach(member => {
        member.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        member.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

function animateNumber(element, start, end, suffix, duration) {
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.floor(start + (end - start) * progress);
        element.textContent = current + suffix;
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}
</script>
@endpush