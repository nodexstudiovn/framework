@extends('layouts.app')

@section('title', 'NodeX Realtime Chat — Micro-Reverb Engine')

@section('content')
<section class="max-w-4xl mx-auto space-y-6">
    <!-- Header Title -->
    <div class="glass-card p-6 rounded-2xl border border-zinc-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h1 class="text-xl font-semibold text-zinc-100">NodeX Realtime Chat</h1>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-zinc-800 border border-zinc-700 text-zinc-300">MICRO-REVERB ENGINE</span>
            </div>
            <p class="text-xs text-zinc-400 mt-1">Hệ thống Chat Realtime dựa trên Redis Pub/Sub & WebSocket Server phân tách hoàn toàn với PHP Framework.</p>
        </div>

        <div class="flex items-center space-x-2">
            <span id="ws-status-badge" class="px-3 py-1 rounded-full text-xs font-mono bg-zinc-900 border border-zinc-800 text-zinc-400">
                Đang kết nối WebSocket...
            </span>
        </div>
    </div>

    <!-- Chat Messages Container -->
    <div class="glass-card rounded-2xl border border-zinc-800 overflow-hidden flex flex-col h-[520px]">
        <!-- Top Info Bar -->
        <div class="px-6 py-3 bg-zinc-900/60 border-b border-zinc-800 flex items-center justify-between text-xs font-mono text-zinc-400">
            <span>PUBLIC_ROOM #1</span>
            <span id="online-counter">1 Online</span>
        </div>

        <!-- Scrollable Message List -->
        <div id="chat-messages" class="flex-1 p-6 overflow-y-auto space-y-4 font-sans text-sm">
            <div class="text-center text-xs text-zinc-400 font-mono py-8">
                Đang tải lịch sử tin nhắn...
            </div>
        </div>

        <!-- Input Box & Actions -->
        <form id="chat-form" class="p-4 bg-zinc-900/80 border-t border-zinc-800 flex items-center space-x-3">
            @csrf
            <input type="text" id="user-name-input" value="Đạt (Cộng sự)" placeholder="Tên của bạn..." class="w-36 px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-xl text-xs text-zinc-200 focus:outline-none focus:border-zinc-700 font-mono" />
            <input type="text" id="message-input" placeholder="Nhập tin nhắn tin nhắn của bạn ở đây..." autocomplete="off" class="flex-1 px-4 py-2 bg-zinc-950 border border-zinc-800 rounded-xl text-sm text-zinc-100 placeholder-zinc-400 focus:outline-none focus:border-zinc-700" />
            <button type="submit" id="send-btn" class="px-5 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-900 font-medium text-xs rounded-xl transition-colors flex items-center space-x-1.5 shadow-lg">
                <span>Gửi</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>
</section>
@endsection
