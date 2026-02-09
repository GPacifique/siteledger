<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiteLedger')</title>

    <!-- DNS Prefetch for faster loading -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Critical CSS inlined for instant rendering -->
    <style>
        {{ file_get_contents(public_path('css/critical.css')) }}
    </style>

    <!-- Modern CSS loaded asynchronously for better performance -->
    <link rel="preload" href="{{ asset('css/modern.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/modern.css') }}"></noscript>

    <!-- Optimized Google Fonts with font-display: swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>

    @yield('head')
    @yield('styles')
</head>
<body class="page-wrapper">
    <!-- Enhanced Navigation with Dropdowns -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="page-content fade-in" style="padding: 2rem 1rem;">
        <div class="container" style="max-width: 1200px; margin: 0 auto;">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Performance optimization script -->
    <script src="{{ asset('js/performance.js') }}" defer></script>

    <style>
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .page-content {
            flex: 1;
            padding-top: 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .alert {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .fade {
            opacity: 1;
        }

        .fade.show {
            opacity: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-content {
                padding: 1rem 0;
            }

            .container {
                padding: 0 0.5rem;
            }
        }
    </style>

    @yield('scripts')
</body>
</html>
