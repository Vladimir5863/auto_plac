<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auto Plac')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #007bff !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .badge {
            font-size: 0.75rem;
        }
        .user-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
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
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Početna
                        </a>
                    </li>

                    <!-- Oglasi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-list me-1"></i>Oglasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('oglasi.index') }}">
                                <i class="fas fa-list me-2"></i>Svi oglasi
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('oglasi.create') }}">
                                <i class="fas fa-plus me-2"></i>Dodaj oglas
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('oglasi.featured') }}">
                                <i class="fas fa-star me-2"></i>Istaknuti oglasi
                            </a></li>
                        </ul>
                    </li>

                    <!-- Vozila -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-car me-1"></i>Vozila
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('vozila.index') }}">
                                <i class="fas fa-list me-2"></i>Sva vozila
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('vozila.create') }}">
                                <i class="fas fa-plus me-2"></i>Dodaj vozilo
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('vozila.search') }}">
                                <i class="fas fa-search me-2"></i>Pretraži vozila
                            </a></li>
                        </ul>
                    </li>

                    <!-- Uplate -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('uplate.index') }}">
                            <i class="fas fa-credit-card me-1"></i>Uplate
                        </a>
                    </li>

                    <!-- Izveštaji -->
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
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('izvestaji.monthly') }}">
                                <i class="fas fa-calendar me-2"></i>Mesečni izveštaj
                            </a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Right Side Navigation -->
                <ul class="navbar-nav">
                    <!-- Search -->
                    <li class="nav-item">
                        <form class="d-flex" action="{{ route('search') }}" method="GET">
                            <input class="form-control me-2" type="search" name="q" placeholder="Pretraži..." style="width: 200px;">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Obaveštenja</h6></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-star text-warning me-2"></i>Novi istaknuti oglas
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-credit-card text-success me-2"></i>Uplata primljena
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>Oglas ističe uskoro
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">Prikaži sva obaveštenja</a></li>
                        </ul>
                    </li>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
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
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('settings') }}">
                                    <i class="fas fa-cog me-2"></i>Podešavanja
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
    <main class="container-fluid py-4">
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
</body>
</html>
