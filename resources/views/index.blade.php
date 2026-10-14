@extends('layouts.app')

@section('title', 'NodeX Studio Framework v1.1.0 — High Performance PHP Framework')

@section('content')
<!-- Hero Section -->
<section class="glass-card rounded-2xl overflow-hidden border border-zinc-800/80 p-8 sm:p-12 relative bg-zinc-900/40 backdrop-blur-xl">
    <div class="space-y-6 max-w-3xl">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-zinc-900 border border-zinc-700/60 text-xs font-mono text-zinc-300">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>SYSTEM_ONLINE // NODEX STUDIO FRAMEWORK v1.1.0</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-zinc-100 leading-tight">
            Next-Gen PHP Framework for Modern Web Architects.
        </h1>

        <p class="text-sm sm:text-base text-zinc-400 leading-relaxed">
            NodeX Studio is a minimalist, ultra-fast PHP 8.4+ micro-framework powered by Laravel Eloquent ORM v13, Laravel Blade Engine, Vite Asset Pipeline, and strict architectural standards.
        </p>

        <div class="flex flex-wrap gap-2 pt-2">
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">PHP 8.4+</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Eloquent ORM v13</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Blade Engine</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Vite Asset Pipeline</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Queue & Redis</span>
            <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Strict View Hygiene</span>
        </div>
    </div>
</section>

<!-- Feature Grid -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono text-sm text-zinc-200">
            01
        </div>
        <h2 class="text-base font-semibold text-zinc-100">Hybrid Action Pattern</h2>
        <p class="text-xs text-zinc-400 leading-relaxed">
            100% business logic is isolated into dedicated Action classes in <code class="text-zinc-300 font-mono">app/Actions/</code>. Thin controllers receive requests, validate, call actions, and return responses.
        </p>
    </div>

    <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono text-sm text-zinc-200">
            02
        </div>
        <h2 class="text-base font-semibold text-zinc-100">Strict View Hygiene</h2>
        <p class="text-xs text-zinc-400 leading-relaxed">
            Zero inline PHP, CSS, or JS in Blade templates. All views must be pure HTML with modular stylesheets and scripts bundled via Vite. Violated templates are blocked at runtime.
        </p>
    </div>

    <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 space-y-3">
        <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono text-sm text-zinc-200">
            03
        </div>
        <h2 class="text-base font-semibold text-zinc-100">Enterprise Security</h2>
        <p class="text-xs text-zinc-400 leading-relaxed">
            Automatic CSRF protection via pipeline middleware, pessimistic concurrency locking for financial records, and dependency injection container with automatic reflection resolution.
        </p>
    </div>
</section>

<!-- Getting Started Terminal Section -->
<section class="p-6 sm:p-8 rounded-2xl bg-zinc-900/60 border border-zinc-800 space-y-4">
    <div class="flex items-center justify-between">
        <div class="text-xs font-mono uppercase tracking-wider text-zinc-400">GETTING_STARTED // CLI_COMMANDS</div>
        <div class="text-xs font-mono text-emerald-400">NodeX CLI Ready</div>
    </div>

    <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800 text-xs font-mono space-y-2 text-zinc-300 overflow-x-auto">
        <p class="text-zinc-500"># Khởi chạy Local Development Server (Cổng 8000)</p>
        <p><span class="text-emerald-400">$</span> php nodex serve</p>
        <p class="text-zinc-500 pt-2"># Khởi chạy Vite Hot Module Reloading (Cổng 5173)</p>
        <p><span class="text-emerald-400">$</span> npm run dev</p>
        <p class="text-zinc-500 pt-2"># Tạo Action nghiệp vụ mới</p>
        <p><span class="text-emerald-400">$</span> php nodex make:action ProcessUserRegistration</p>
        <p class="text-zinc-500 pt-2"># Khởi chạy tiến trình Queue Worker</p>
        <p><span class="text-emerald-400">$</span> php nodex queue:work</p>
    </div>
</section>
@endsection
