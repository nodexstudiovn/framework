@extends('layouts.app')

@section('title', 'NodeX Studio Framework v1.1.0 — Nền Tảng PHP Hiện Đại Hiệu Năng Cao')

@section('content')
<!-- Hero Section -->
<section class="glass-card rounded-2xl overflow-hidden border border-zinc-800/80 p-8 sm:p-12 relative bg-zinc-900/40 backdrop-blur-xl">
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-700/80 p-2 shadow-2xl flex items-center justify-center">
                @include('partials.logo')
            </div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-zinc-900 border border-zinc-700/60 text-xs font-mono text-zinc-300">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>HỆ THỐNG TRỰC TUYẾN // NODEX STUDIO FRAMEWORK v1.1.0</span>
            </div>
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-zinc-100 leading-tight">
            Framework PHP Thế Hệ Mới Dành Cho Lập Trình Viên Hiện Đại.
        </h1>

        <p class="text-sm sm:text-base text-zinc-400 leading-relaxed font-normal">
            NodeX Studio là một micro-framework PHP 8.4+ tinh gọn, tốc độ vượt trội, tích hợp sẵn Eloquent ORM v13, Blade Engine, Vite Asset Pipeline, hệ thống Hàng Đợi (Queue), Realtime WebSockets và quy chuẩn kiến trúc sạch nghiêm ngặt.
        </p>

        <div class="flex flex-wrap gap-2 pt-2">
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">PHP 8.4+</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Eloquent ORM v13</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Blade Engine</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Vite Asset Pipeline</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Hàng Đợi & Redis</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Bảo Mật CSRF</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Vệ Sinh View Khắt Khe</span>
        </div>
    </div>
</section>

<!-- Feature Grid -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono text-sm text-zinc-200">
            01
        </div>
        <h2 class="text-base font-semibold text-zinc-100">Kiến Trúc Hybrid Action</h2>
        <p class="text-xs text-zinc-400 leading-relaxed">
            100% nghiệp vụ logic được tách biệt độc lập trong các lớp Action tại <code class="text-zinc-300 font-mono">app/Actions/</code>. Controller cực mỏng chỉ tiếp nhận Request, Validate, gọi Action và trả về Response (JSON hoặc View).
        </p>
    </div>

    <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono text-sm text-zinc-200">
            02
        </div>
        <h2 class="text-base font-semibold text-zinc-100">Chuẩn Mực View Hygiene</h2>
        <p class="text-xs text-zinc-400 leading-relaxed">
            Tuyệt đối không nhúng mã PHP, CSS hoặc JS trực tiếp trong file View Blade. Mọi giao diện đều là HTML thuần với asset module hoá được đóng gói qua Vite. Hệ thống tự động chặn lỗi vi phạm khi chạy ứng dụng.
        </p>
    </div>

    <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono text-sm text-zinc-200">
            03
        </div>
        <h2 class="text-base font-semibold text-zinc-100">Bảo Mật & Khóa Dữ Liệu</h2>
        <p class="text-xs text-zinc-400 leading-relaxed">
            Bảo mật CSRF token tự động qua Pipeline Middleware, cơ chế khoá bi quan (pessimistic locking) chống tranh chấp dữ liệu tài chính, và Container tiêm phụ thuộc (DI) tự động giải quyết bằng Reflection.
        </p>
    </div>
</section>

<!-- Getting Started Terminal Section -->
<section class="p-6 sm:p-8 rounded-2xl bg-zinc-900/60 border border-zinc-800 space-y-4">
    <div class="flex items-center justify-between">
        <div class="text-xs font-mono uppercase tracking-wider text-zinc-400">BẮT_ĐẦU_NGAY // LỆNH_NODEX_CLI</div>
        <div class="text-xs font-mono text-emerald-400">NodeX CLI Sẵn Sàng</div>
    </div>

    <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800 text-xs font-mono space-y-2 text-zinc-300 overflow-x-auto">
        <p class="text-zinc-500"># Khởi chạy máy chủ phát triển nội bộ (Cổng 8000)</p>
        <p><span class="text-emerald-400">$</span> php nodex serve</p>
        <p class="text-zinc-500 pt-2"># Khởi chạy Vite Hot Module Reloading (Cổng 5173)</p>
        <p><span class="text-emerald-400">$</span> npm run dev</p>
        <p class="text-zinc-500 pt-2"># Tạo một lớp Action nghiệp vụ mới</p>
        <p><span class="text-emerald-400">$</span> php nodex make:action ProcessUserRegistration</p>
        <p class="text-zinc-500 pt-2"># Khởi chạy tiến trình lắng nghe Hàng Đợi Queue Worker</p>
        <p><span class="text-emerald-400">$</span> php nodex queue:work</p>
        <p class="text-zinc-500 pt-2"># Khởi chạy máy chủ WebSockets Realtime</p>
        <p><span class="text-emerald-400">$</span> php nodex realtime:serve</p>
    </div>
</section>
@endsection
