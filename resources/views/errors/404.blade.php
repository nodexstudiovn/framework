<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Không Tìm Thấy Trang | NodeX Studio</title>
    <link rel="icon" type="image/svg+xml" href="/assets/logo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#090d16] text-slate-100 font-sans min-h-screen flex items-center justify-center p-6 selection:bg-indigo-500 selection:text-white">
    <div class="max-w-lg w-full text-center space-y-6">
        <div class="relative inline-block">
            <div class="w-24 h-24 mx-auto rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-2xl">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="absolute -bottom-2 -right-2 px-2.5 py-0.5 rounded-full bg-indigo-500 text-white font-mono text-xs font-bold">404</span>
        </div>

        <div class="space-y-2">
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Không tìm thấy trang</h1>
            <p class="text-slate-400 text-sm leading-relaxed">
                Đường dẫn bạn yêu cầu không tồn tại trên hệ thống hoặc đã được di chuyển sang địa chỉ mới.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="/" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 transition-all">
                Về trang chủ
            </a>
            <button onclick="history.back()" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-sm border border-slate-700 transition-all">
                Quay lại trang trước
            </button>
        </div>

        <p class="text-xs text-slate-600 font-mono pt-6">NodeX Studio Framework • 404 Not Found</p>
    </div>
</body>
</html>
