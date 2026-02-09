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

    <!-- Colorful Theme CSS - Loaded synchronously for consistent colors -->
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">

    <!-- Modern CSS loaded asynchronously for better performance -->
    <link rel="preload" href="{{ asset('css/modern.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/modern.css') }}"></noscript>

    <!-- Optimized Google Fonts with font-display: swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>

    <!-- Faster CSS loading script -->
    <script>
        !function(e){"use strict";var t=function(t,n,r,a){var o=e.document.createElement("link");return o.rel="stylesheet",o.href=t,o.media="only x",a&&(o.onload=a),e.document.head.appendChild(o),setTimeout(function(){o.media=n||"all"})},n=function(t){return e.document.querySelector('link[href="'+t+'"]')};e.loadCSS=function(e,r,a,o){var c=n(e);if(c)return o&&o(),c;var l=t(e,r,a,o);return l},"undefined"!=typeof exports?exports.loadCSS=e.loadCSS:e.loadCSS=e.loadCSS}("undefined"!=typeof global?global:this);
    </script>

    @yield('head')
    @yield('styles')
</head>
<body class="page-wrapper">
    <!-- Enhanced Navigation with Dropdowns -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="page-content fade-in">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Performance optimization script -->
    <script src="{{ asset('js/performance.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
