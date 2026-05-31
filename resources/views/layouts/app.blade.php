<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BeatHub') }} - Online Beat Selling Platform</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #000 !important;
            border-bottom: 1px solid #333;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-brand i {
            color: #e63946;
            margin-right: 8px;
        }

        /* Make navbar always expanded on all screens */
        .navbar-collapse {
            display: flex !important;
            flex-basis: auto;
        }

        .navbar-toggler {
            display: none !important;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #e63946 !important;
        }

        /* Search bar styling */
        .search-form {
            width: 500px;
            margin: 0 auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .search-form .form-control {
            background: #222;
            border: 1px solid #333;
            color: #fff;
            border-radius: 25px 0 0 25px;
            padding: 12px 25px;
            width: 400px;
        }

        .search-form .btn {
            border-radius: 0 25px 25px 0;
            background: #e63946;
            border-color: #e63946;
            color: white;
            padding: 12px 25px;
        }

        .search-form .btn:hover {
            background: #c1121f;
        }

        /* Make navbar container relative for absolute positioning */
        .navbar .container {
            position: relative;
        }

        /* Adjust right icons to not overlap */
        .right-icons {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
        }

        .btn-primary {
            background: #e63946;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #c1121f;
            transform: translateY(-2px);
        }

        /* Card styles */
        .card {
            background: #111;
            border: 1px solid #222;
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: #e63946;
        }

        /* Category sidebar styles */
        .list-group-item {
            background-color: #111 !important;
            color: #fff !important;
            border-color: #222 !important;
            transition: all 0.3s;
        }

        .list-group-item:hover {
            background-color: #e63946 !important;
            color: #fff !important;
            transform: translateX(5px);
        }

        .list-group-item.active {
            background-color: #e63946 !important;
            border-color: #e63946 !important;
        }

        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #e63946;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }

        /* Footer positioning */
        footer {
            background: #000;
            padding: 30px 0;
            margin-top: auto;
            border-top: 1px solid #222;
            text-align: center;
        }

        main {
            flex: 1;
        }

        /* Audio player */
        .audio-preview {
            background: #1a1a1a;
            padding: 10px;
            border-radius: 8px;
        }

        audio {
            width: 100%;
        }

        /* Tags */
        .tag {
            display: inline-block;
            background: #222;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin: 2px;
            color: #aaa;
        }

        /* Hero section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            margin-bottom: 50px;
            border-radius: 0 0 30px 30px;
        }

        /* Dropdown menu */
        .dropdown-menu {
            background: #1a1a1a;
            border: 1px solid #333;
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background: #e63946;
            color: #fff;
        }

        /* Icon links with hover tooltip */
        .icon-link {
            color: #fff;
            font-size: 1.2rem;
            margin-left: 20px;
            position: relative;
            text-decoration: none;
            display: inline-block;
        }

        .icon-link:hover {
            color: #e63946;
        }

        /* Tooltip text that appears on hover */
        .icon-link .hover-text {
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 100;
            pointer-events: none;
        }

        /* Tooltip arrow */
        .icon-link .hover-text::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 5px solid #333;
        }

        /* Show tooltip on hover */
        .icon-link:hover .hover-text {
            opacity: 1;
            visibility: visible;
        }

        /* Right side icons container */
        .right-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-form {
                width: 200px;
            }

            .search-form .form-control {
                width: 150px;
            }

            .nav-link span {
                display: none;
            }

            .icon-link .hover-text {
                display: none;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <!-- Logo Left -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-headphones"></i> AnmolBeats
            </a>

            <!-- Navigation Links (hidden on mobile but we keep) -->
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('beats.index') }}">
                            <i class="fas fa-music"></i> <span>Browse Beats</span>
                        </a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.history') }}">
                            <i class="fas fa-history"></i> <span>My Orders</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> <span>Admin</span>
                        </a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </div>

            <!-- Search Bar in Middle -->
            <form action="{{ route('beats.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search beats..." value="{{ request('search') }}">
                    <button class="btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right Side Icons -->
            <div class="right-icons">
                <!-- Cart Icon -->
                <a class="icon-link position-relative" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span class="hover-text">Cart</span>
                    @php
                    $cartCount = count(session()->get('cart', []));
                    @endphp
                    @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- User Menu -->
                @guest
                <a class="icon-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt fa-lg"></i>
                    <span class="hover-text">Login</span>
                </a>
                <a class="icon-link" href="{{ route('register') }}">
                    <i class="fas fa-user-plus fa-lg"></i>
                    <span class="hover-text">Register</span>
                </a>
                @else
                <div class="dropdown">
                    <a class="icon-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <span class="hover-text">Account</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item text-muted">{{ auth()->user()->name }}</span></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('orders.history') }}">
                                <i class="fas fa-box"></i> My Orders
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Admin Panel
                            </a>
                        </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-headphones"></i> AnmolBeats</h5>
                    <p class="text-muted">Premium beats for your next project</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('beats.index') }}" class="text-muted text-decoration-none">Browse Beats</a></li>
                        @auth
                        <li><a href="{{ route('orders.history') }}" class="text-muted text-decoration-none">My Orders</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <a href="#" class="text-muted me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-muted me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-muted me-3"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="mb-0">© 2026 AnmolBeats - Online Beat Selling Platform. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Floating Player Button -->
    <button class="show-player-btn" onclick="showPlayer()">
        <i class="fas fa-headphones"></i>
    </button>

    <!-- Include the music player component -->
    @include('components.music-player')

    <script>
        // Close player when clicking outside (optional)
        document.addEventListener('click', function(event) {
            const player = document.getElementById('floatingPlayer');
            const playerBtn = document.querySelector('.show-player-btn');
            if (player && player.classList.contains('show')) {
                if (!player.contains(event.target) && event.target !== playerBtn && !playerBtn.contains(event.target)) {
                    hidePlayer();
                }
            }
        });
    </script>
    <!-- Music Player JavaScript -->
    <script src="{{ asset('js/music-player.js') }}"></script>
</body>

</html>