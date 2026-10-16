<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NodeX Studio — Thông Báo Hệ Thống')</title>
    <link rel="icon" type="image/svg+xml" href="/assets/logo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/error.css">
    <script src="/assets/js/error.js" defer></script>
</head>
<body class="bg-[#090d16] text-slate-200 font-sans min-h-screen antialiased selection:bg-rose-500 selection:text-white">
    @yield('content')
</body>
</html>
