<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @csrf_meta
    <title>@yield('title', 'Nguyễn Anh Kiệt — Software Engineer & UI/UX Designer')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets (Tailwind CSS Compiled locally without CDN) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-obsidian text-zinc-100 font-sans antialiased selection:bg-zinc-100 selection:text-zinc-900 min-h-screen" x-data="{ activeTab: 'overview', mobileMenu: false, copyToast: false }">

    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-6 py-12 space-y-12">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
