@extends('layouts.error')

@section('title', '500 — Máy Chủ Gặp Sự Cố | NodeX Studio')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-6 selection:bg-rose-500 selection:text-white">
        <div class="max-w-lg w-full text-center space-y-6">
            <div class="relative inline-block">
                <div class="w-24 h-24 mx-auto rounded-3xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-500 shadow-2xl">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="absolute -bottom-2 -right-2 px-2.5 py-0.5 rounded-full bg-rose-500 text-white font-mono text-xs font-bold">500</span>
            </div>

            <div class="space-y-2">
                <h1 class="text-3xl font-extrabold tracking-tight text-white">Sự cố máy chủ nội bộ</h1>
                <p class="text-slate-400 text-sm leading-relaxed">
                    {{ $message ?? 'Hệ thống đang gặp sự cố gián đoạn tạm thời. Đội ngũ kỹ sư của NodeX Studio đã nhận được báo cáo và đang khôi phục dịch vụ.' }}
                </p>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="/" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 transition-all">
                    Quay về trang chủ
                </a>
                <button id="reloadBtn" type="button" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-sm border border-slate-700 transition-all">
                    Tải lại trang
                </button>
            </div>

            <p class="text-xs text-slate-600 font-mono pt-6">NodeX Studio Framework • Production Safe Mode</p>
        </div>
    </div>
@endsection
