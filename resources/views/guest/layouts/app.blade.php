<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PAS Market - Platform penjualan produk terpercaya">
    <meta name="keywords" content="pas market, e-commerce, produk, belanja online">
    <title>@yield('title', 'PAS Market - Belanja Online Terpercaya')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('guest/css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100" @if(Request::is('login', 'register')) data-spa="false" @endif>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                <span class="pas-brand-text">PAS</span><span class="pas-brand-sub">Market</span>
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Bar -->
                <div class="mx-auto d-none d-lg-block" style="width: 50%;">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari produk..." id="searchInput">
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="bi bi-house"></i><span class="ms-2 d-lg-none">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/categories') }}">
                            <i class="bi bi-tags"></i><span class="ms-2 d-lg-none">Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/products') }}">
                            <i class="bi bi-grid"></i><span class="ms-2 d-lg-none">Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#promo') }}">
                            <i class="bi bi-percent"></i><span class="ms-2 d-lg-none">Promo</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-cart-link position-relative" href="{{ (Auth::guard('customer')->check() || (Auth::guard('web')->check() && Auth::guard('web')->user()->isSales())) ? url('/cart') : (url('/login').'?redirect='.urlencode('/cart')) }}" id="cartBtn">
                            <span class="nav-cart-left">
                                <i class="bi bi-cart3"></i><span class="ms-2 d-lg-none">Keranjang</span>
                            </span>
                            <span class="badge rounded-pill bg-danger nav-cart-badge" id="cartCount" style="display: none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ (Auth::guard('customer')->check() || (Auth::guard('web')->check() && Auth::guard('web')->user()->isSales())) ? url('/profile') : (url('/login').'?redirect='.urlencode('/profile')) }}">
                            <i class="bi bi-person"></i><span class="ms-2 d-lg-none">Akun</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Mobile Search -->
    <div class="container d-lg-none mt-2 mb-2">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari produk..." id="searchInputMobile">
            <button class="btn btn-primary" type="button" id="searchBtnMobile">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        <!-- Global Alerts -->
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-shop"></i> PAS Market
                    </h5>
                    <p class="text-light">
                        Platform penjualan produk terpercaya dengan kualitas terjamin dan harga terbaik.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-whatsapp fs-4"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/products') }}" class="text-light text-decoration-none">Elektronik</a></li>
                        <li><a href="{{ url('/products') }}" class="text-light text-decoration-none">Fashion</a></li>
                        <li><a href="{{ url('/products') }}" class="text-light text-decoration-none">Kebutuhan Rumah</a></li>
                        <li><a href="{{ url('/products') }}" class="text-light text-decoration-none">Olahraga</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="fw-bold mb-3">Layanan</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/contact') }}" class="text-light text-decoration-none">Bantuan</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-light text-decoration-none">Pengembalian</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-light text-decoration-none">Pengaduan</a></li>
                        <li><a href="{{ url('/about') }}" class="text-light text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="fw-bold mb-3">Tentang</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/about') }}" class="text-light text-decoration-none">Tentang Kami</a></li>
                        <li><a href="{{ url('/about') }}" class="text-light text-decoration-none">Karir</a></li>
                        <li><a href="{{ url('/about') }}" class="text-light text-decoration-none">Blog</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-light text-decoration-none">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="fw-bold mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled">
                        <li class="text-light">
                            <i class="bi bi-geo-alt"></i> Jakarta, Indonesia
                        </li>
                        <li class="text-light">
                            <i class="bi bi-telephone"></i> +62 812-3456-7890
                        </li>
                        <li class="text-light">
                            <i class="bi bi-envelope"></i> info@pasmarket.com
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light">&copy; 2024 PAS Market. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <img src="{{ asset('guest/img/placeholder-payment.svg') }}" alt="Payment Methods" class="img-fluid" style="max-height: 30px;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button type="button" class="btn btn-primary btn-lg rounded-circle position-fixed bottom-0 end-0 m-3" id="backToTop" style="display: none; z-index: 1050;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Loading Indicators -->
    <div id="global-loading-overlay" style="display: none;">
        <div class="text-center">
            <div class="loading-spinner mb-3"></div>
            <div style="font-size: 18px; font-weight: 500; color: #333;">Memuat data...</div>
            <div style="font-size: 14px; color: #666; margin-top: 8px;">Mohon tunggu sebentar</div>
        </div>
    </div>
    <div id="page-loading-indicator" style="display: none;"></div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Core Application JS -->
    <script src="{{ asset('guest/js/app.js?v='.(@filemtime(public_path('guest/js/app.js')) ?: time())) }}"></script>
    <!-- Router System -->
    <script src="{{ asset('guest/js/router.js') }}"></script>
    <!-- Product Interactions -->
    <script src="{{ asset('guest/js/products.js?v='.(@filemtime(public_path('guest/js/products.js')) ?: time())) }}"></script>
    <!-- Search & Navigation -->
    <script src="{{ asset('guest/js/search.js') }}"></script>
    <!-- Loading System -->
    <script src="{{ asset('guest/js/loading.js') }}"></script>
    
    @stack('scripts')
    <script>
        window.PAS = window.PAS || {};
        window.PAS.auth = {
            loggedIn: {{ (Auth::guard('customer')->check() || (Auth::guard('web')->check() && Auth::guard('web')->user()->isSales())) ? 'true' : 'false' }},
            user: @json(Auth::guard('customer')->user() ?? (Auth::guard('web')->check() && Auth::guard('web')->user()->isSales() ? Auth::guard('web')->user() : null)),
            loginUrl: '{{ route('guest.login') }}'
        };
        window.PAS.urls = {
            sync: '{{ url('/api/guest/sync') }}',
            home: '{{ url('/api/guest/home') }}',
            orders: '{{ url('/api/guest/orders') }}',
            products: '{{ url('/api/guest/products') }}',
            productShow: '{{ url('/api/guest/products') }}',
            cart: '{{ route('guest.cart.index') }}'
        };
        window.PAS.initialScreen = '{{ $initialScreen ?? 'home' }}';
        window.PAS.initialProductId = '{{ $initialProductId ?? '' }}';
    </script>
</body>
</html>
