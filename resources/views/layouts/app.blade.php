<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WINWIN Makeup')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding-top: 0;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            z-index: 1000;
        }
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 30px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 600;
            color: #1a1a1a !important;
            font-size: 1.3rem;
            letter-spacing: -0.5px;
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }
        .nav-link:hover {
            color: #000 !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 80%;
            height: 2px;
            background: #1a1a1a;
            transition: transform 0.3s ease;
        }
        .nav-link:hover::after {
            transform: translateX(-50%) scaleX(1);
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #1a1a1a;
            border-color: #1a1a1a;
        }
        .btn-primary:hover {
            background-color: #000;
            border-color: #000;
        }
        .btn-logout {
            background: transparent;
            border: 1px solid #1a1a1a;
            color: #1a1a1a;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-logout:hover {
            background: #1a1a1a;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }

            .nav-link {
                font-size: 0.9rem;
                padding: 10px 15px !important;
            }

            .btn-logout {
                width: 100%;
                margin: 10px 0 0 0;
                text-align: center;
            }

            .navbar-collapse {
                margin-top: 15px;
            }

            .navbar-nav {
                text-align: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">WINWIN Makeup</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-logout">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 80px;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 80px;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" style="margin-top: 80px;">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
    </main>

    <footer>
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} WINWIN Makeup. All rights reserved.</p>
        </div>
    </footer>

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

