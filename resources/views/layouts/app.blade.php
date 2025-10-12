<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Auto Plac</title>
    <!-- SVG favicon (car icon) -->
    <link rel="icon" type="image/svg+xml" href='data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"%3E%3Crect width="64" height="64" rx="12" fill="%237c3aed"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="34"%3E%F0%9F%9A%97%3C/text%3E%3C/svg%3E'>
    <!-- ICO fallback -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #a78bfa; /* light purple */
            --bs-primary-rgb: 167, 139, 250;
            --bs-link-color: #7c6ee6;
            --bs-link-hover-color: #6b5edb;
        }
        body {
            background-color: #f7f5ff;
        }
        .bg-purple {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--bs-primary) !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item:hover {
            background-color: #f1edff;
        }
        .badge {
            font-size: 0.75rem;
        }
        .user-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover {
            filter: brightness(0.95);
        }
        .btn-outline-primary {
            color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--bs-primary);
            color: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(var(--bs-primary-rgb), 1);
            box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .25);
        }
        /* Tweak Chrome autofill yellow */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
            -webkit-text-fill-color: #212529;
            transition: background-color 5000s ease-in-out 0s;
            box-shadow: 0 0 0px 1000px #fff inset;
        }
        .card {
            border: none;
            border-radius: .75rem;
        }
        .card-header {
            border-top-left-radius: .75rem !important;
            border-top-right-radius: .75rem !important;
        }
        /* Admin tables: align actions column baseline */
        table .actions { white-space: nowrap; }
        table td.actions, table th.actions { vertical-align: middle; padding-top: .75rem; padding-bottom: .75rem; }
        table td.actions form { display: inline-block; margin: 0; }
        table td.actions .btn { vertical-align: middle; }
        /* Sticky footer: body as flex column */
        html, body { height: 100%; }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main.site-main { flex: 1 0 auto; }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-purple sticky-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-car me-2"></i>Auto Plac
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Početna
                        </a>
                    </li>

                    <!-- Sva vozila -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vehicles.index') }}">
                            Sva vozila
                        </a>
                    </li>

                    <!-- Moji oglasi (submenu) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-list me-1"></i>Moji oglasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('ads.index') }}">
                                <i class="fas fa-list me-2"></i>Svi oglasi
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('ads.featured') }}">
                                <i class="fas fa-star me-2"></i>Istaknuti oglasi
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('ads.create') }}">
                                <i class="fas fa-plus me-2"></i>Kreiraj oglas
                            </a></li>
                        </ul>
                    </li>

                    

                    <!-- Uplate -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('uplate.index') }}">
                            <i class="fas fa-credit-card me-1"></i>Uplate
                        </a>
                    </li>

                    <!-- Admin (only for admin users) -->
                    @auth
                        @if(Auth::user()->tipKorisnika === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-chart-bar me-1"></i>Izveštaji
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('izvestaji.index') }}">
                                        <i class="fas fa-list me-2"></i>Svi izveštaji
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('izvestaji.create') }}">
                                        <i class="fas fa-plus me-2"></i>Novi izveštaj
                                    </a>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Right Side Navigation -->
                <ul class="navbar-nav">

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="user-info">
                                @auth
                                    {{ Auth::user()->korisnickoIme ?? 'Korisnik' }}
                                @else
                                    Gost
                                @endauth
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @auth
                                <li><h6 class="dropdown-header">Moj nalog</h6></li>
                                <li><a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-2"></i>Profil
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('oglasi.my') }}">
                                    <i class="fas fa-list me-2"></i>Moji oglasi
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('uplate.my') }}">
                                    <i class="fas fa-credit-card me-2"></i>Moje uplate
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.purchases') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>Kupljena vozila
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Odjavi se
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-2"></i>Prijavi se
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-2"></i>Registruj se
                                </a></li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid py-4 site-main">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-car me-2"></i>Auto Plac</h5>
                    <p class="mb-0">Vaš pouzdan partner za prodaju vozila</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>+381 11 123 4567 |
                        <i class="fas fa-envelope me-2"></i>info@autoplac.rs
                    </p>
                    <p class="mb-0">
                        <small>&copy; {{ date('Y') }} Auto Plac. Sva prava zadržana.</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // CSRF token setup for AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (token) {
            window.Laravel = {
                csrfToken: token
            };
        }
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
