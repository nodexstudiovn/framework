@extends('layouts.app')

@section('title', 'NodeX Studio — Blade & Vite Powered')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="glass-card max-w-xl w-full p-8 rounded-2xl border border-zinc-800 space-y-6 bg-zinc-900/60 backdrop-blur-xl">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center font-mono font-bold text-zinc-100">
                NK
            </div>
            <div>
                <h1 class="text-xl font-bold text-zinc-100">NodeX Studio Framework</h1>
                <p class="text-xs text-zinc-400 font-mono">v1.1.0 — Blade & Vite Active</p>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-zinc-950/80 border border-zinc-800/80 text-xs space-y-2 font-mono">
            <div class="text-emerald-400 font-bold">● System Status: Operational</div>
            <div>PHP Engine: <span class="text-zinc-300">{{ PHP_VERSION }}</span></div>
            <div>Blade Template: <span class="text-zinc-300">Laravel Blade Engine v13.x</span></div>
            <div>Vite Asset Pipeline: <span class="text-zinc-300">Vite 6.x</span></div>
        </div>

        <div class="flex items-center justify-between text-xs text-zinc-500 font-mono pt-2 border-t border-zinc-800">
            <span>© 2026 NodeX Studio</span>
            <a href="/api/v1/info" class="text-zinc-300 hover:text-white transition-colors">API Info ↗</a>
        </div>
    </div>
</div>
@endsection
