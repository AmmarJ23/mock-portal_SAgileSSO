<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Mock Portal') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #ffffff !important;
            transform: translateY(-1px);
        }
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #667eea;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .btn-custom {
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Mock Portal') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sso.login.form') }}" class="nav-link">Access SAgilePMT</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Welcome to the UTM Mock Portal</h1>
            <p class="lead mb-5">Your gateway to seamless integration with SAgilePMT and other UTM services</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-light btn-custom me-3">Get Started</a>
            @endif
            @auth
                <a href="{{ route('sso.login.form') }}" class="btn btn-outline-light btn-custom">Access SAgilePMT</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom">Sign In</a>
            @endauth
        </div>
    </section>

    <section class="features py-5">
        <div class="container">
            <h2 class="text-center mb-5">Portal Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="feature-icon text-primary">🔐</div>
                        <h3 class="h5 mb-3">Single Sign-On (SSO)</h3>
                        <p class="text-muted">Access SAgilePMT seamlessly with your portal credentials. One login, multiple services.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="feature-icon text-primary">📝</div>
                        <h3 class="h5 mb-3">Easy Registration</h3>
                        <p class="text-muted">Quick and secure registration process with your UTM credentials and matric number.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="feature-icon text-primary">🔄</div>
                        <h3 class="h5 mb-3">Synchronized Access</h3>
                        <p class="text-muted">Your information is automatically synchronized between the portal and SAgilePMT.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="getting-started py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Getting Started</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <ol class="mb-0">
                                <li class="mb-3">Register for an account using your UTM email and matric number</li>
                                <li class="mb-3">Log in to the portal with your credentials</li>
                                <li class="mb-3">Click on "Access SAgilePMT" to use the SSO feature</li>
                                <li>Start using SAgilePMT with your synchronized account</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} UTM Mock Portal. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
