<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo \NodeX\Security\CsrfToken::meta(); ?>
    <title><?php echo $__env->yieldContent('title', 'Nguyễn Anh Kiệt — Software Engineer & UI/UX Designer'); ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets (Tailwind CSS Compiled locally without CDN) -->
    <?php echo \NodeX\View\Vite::use(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-obsidian text-zinc-100 font-sans antialiased selection:bg-zinc-100 selection:text-zinc-900 min-h-screen" x-data="{ activeTab: 'overview', mobileMenu: false, copyToast: false }">

    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="max-w-5xl mx-auto px-6 py-12 space-y-12">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Hi\Downloads\thongtincanhan\resources\views/layouts/app.blade.php ENDPATH**/ ?>