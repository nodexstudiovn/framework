<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shortClass }}: {{ $message }} — NodeX Studio</title>
    <link rel="icon" type="image/svg+xml" href="/assets/logo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"Fira Code"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#090d16] text-slate-200 font-sans min-h-screen antialiased selection:bg-rose-500 selection:text-white pb-16">

    <!-- Top Navigation Header -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50 px-6 py-3.5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2 group">
                    <img src="/assets/logo.svg" alt="NodeX Logo" class="w-8 h-8 rounded-lg shadow-sm">
                    <span class="font-bold text-lg tracking-tight text-white group-hover:text-indigo-400 transition-colors">NodeX Studio</span>
                </a>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">500 EXCEPTION</span>
            </div>

            <div class="flex items-center gap-3 text-xs font-medium">
                <span class="px-2.5 py-1 rounded-md bg-slate-800/80 text-slate-300 border border-slate-700/60 font-mono">PHP {{ $phpVersion }}</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800/80 text-indigo-300 border border-slate-700/60 font-mono">v{{ $nodeXVersion }}</span>
                <button id="copyErrorBtn" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 transition-all font-mono active:scale-95">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span>Sao chép lỗi</span>
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 pt-8 space-y-6">

        <!-- Error Summary Card -->
        <section class="rounded-2xl border border-rose-500/30 bg-gradient-to-b from-rose-950/20 to-slate-900/60 p-6 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-3">
                <div class="flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-rose-400 font-bold">
                    <svg class="w-4 h-4 text-rose-500 inline-block animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ $class }}</span>
                </div>

                <h1 id="errorMessageText" class="text-2xl lg:text-3xl font-bold text-white tracking-tight leading-snug">
                    {{ $message }}
                </h1>

                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900/90 border border-slate-700/80 font-mono text-xs text-slate-300 shadow-inner">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-rose-400 font-semibold">{{ $file }}</span>
                        <span class="text-slate-500">:</span>
                        <span class="text-amber-400 font-bold">Line {{ $line }}</span>
                    </span>
                    <span class="text-xs text-slate-400 font-mono">RAM: {{ $memory }}</span>
                    <span class="text-xs text-slate-400 font-mono">Thời gian: {{ $time }}</span>
                </div>
            </div>
        </section>

        <!-- Code Snippet Viewer (Laravel Ignition Style) -->
        @if (!empty($snippet))
        <section class="rounded-2xl border border-slate-800 bg-[#0d131f] shadow-2xl overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-800/80 bg-slate-900/80 flex items-center justify-between">
                <div class="flex items-center gap-2 font-mono text-xs text-slate-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                    <span class="font-medium text-slate-200">{{ $file }}</span>
                </div>
                <span class="text-xs text-slate-500 font-mono">Vị trí phát sinh ngoại lệ</span>
            </div>

            <div class="overflow-x-auto p-4 font-mono text-xs leading-relaxed">
                <table class="w-full border-collapse">
                    <tbody>
                        @foreach ($snippet as $row)
                        <tr class="{{ $row['is_current'] ? 'bg-rose-500/20 text-rose-200 border-l-4 border-rose-500' : 'hover:bg-slate-800/30 text-slate-400' }}">
                            <td class="w-12 text-right pr-4 select-none py-1 {{ $row['is_current'] ? 'text-rose-400 font-bold' : 'text-slate-600' }}">
                                {{ $row['number'] }}
                            </td>
                            <td class="w-6 text-center select-none py-1">
                                @if ($row['is_current'])
                                <span class="text-rose-500 font-bold">➜</span>
                                @endif
                            </td>
                            <td class="pl-2 py-1 whitespace-pre {{ $row['is_current'] ? 'text-white font-semibold' : 'text-slate-300' }}">{{ $row['code'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        @endif

        <!-- Tabbed Inspector (Stack Trace, Request, Environment) -->
        <section class="rounded-2xl border border-slate-800 bg-slate-900/50 shadow-2xl overflow-hidden">
            <!-- Tab Headers -->
            <div class="border-b border-slate-800 bg-slate-900/90 px-4 flex gap-1">
                <button type="button" data-tab="tab-trace" class="tab-btn px-4 py-3 text-xs font-semibold text-white border-b-2 border-indigo-500 transition-colors">
                    Stack Trace ({{ count($trace) }})
                </button>
                <button type="button" data-tab="tab-request" class="tab-btn px-4 py-3 text-xs font-semibold text-slate-400 hover:text-slate-200 border-b-2 border-transparent transition-colors">
                    Request & Headers
                </button>
                <button type="button" data-tab="tab-env" class="tab-btn px-4 py-3 text-xs font-semibold text-slate-400 hover:text-slate-200 border-b-2 border-transparent transition-colors">
                    Environment & Server
                </button>
            </div>

            <!-- Tab 1: Stack Trace -->
            <div id="tab-trace" class="tab-content p-6 space-y-3">
                @if (empty($trace))
                <p class="text-slate-500 text-xs italic">Không có thông tin stack trace chi tiết.</p>
                @else
                <div class="space-y-2">
                    @foreach ($trace as $step)
                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3.5 hover:border-slate-700 transition-colors">
                        <div class="flex items-start justify-between gap-4 font-mono text-xs">
                            <div class="space-y-1">
                                <div class="font-semibold text-indigo-400">
                                    <span class="text-slate-500 mr-1.5">#{{ $step['index'] }}</span>
                                    {{ $step['caller'] }}
                                </div>
                                <div class="text-slate-400">
                                    {{ $step['file'] }}:<span class="text-amber-400 font-bold">{{ $step['line'] }}</span>
                                </div>
                            </div>
                        </div>

                        @if (!empty($step['snippet']))
                        <div class="mt-3 pt-3 border-t border-slate-800/80 font-mono text-[11px] text-slate-400 bg-slate-900/40 rounded-lg p-2.5">
                            @foreach ($step['snippet'] as $subRow)
                            <div class="flex gap-3 py-0.5 {{ $subRow['is_current'] ? 'text-rose-300 font-bold bg-rose-500/10' : '' }}">
                                <span class="w-8 text-right text-slate-600 select-none">{{ $subRow['number'] }}</span>
                                <span class="whitespace-pre">{{ $subRow['code'] }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Tab 2: Request & Headers -->
            <div id="tab-request" class="tab-content p-6 space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-2">
                        <h3 class="text-xs uppercase tracking-wider font-bold text-slate-400 font-mono">Thông tin Request</h3>
                        <div class="font-mono text-xs space-y-1.5">
                            <div class="flex justify-between py-1 border-b border-slate-800/60">
                                <span class="text-slate-500">Method:</span>
                                <span class="font-bold text-emerald-400">{{ $_SERVER['REQUEST_METHOD'] ?? 'CLI/GET' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-800/60">
                                <span class="text-slate-500">URI:</span>
                                <span class="text-slate-200">{{ $_SERVER['REQUEST_URI'] ?? '/' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-800/60">
                                <span class="text-slate-500">Client IP:</span>
                                <span class="text-slate-300">{{ $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500">Host:</span>
                                <span class="text-slate-300">{{ $_SERVER['HTTP_HOST'] ?? 'localhost' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-2">
                        <h3 class="text-xs uppercase tracking-wider font-bold text-slate-400 font-mono">Query Parameters</h3>
                        <div class="font-mono text-xs">
                            @if (!empty($_GET))
                            <pre class="text-slate-300 overflow-x-auto">{{ json_encode($_GET, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                            <span class="text-slate-500 italic">Không có query parameters ($_GET rỗng).</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-2">
                    <h3 class="text-xs uppercase tracking-wider font-bold text-slate-400 font-mono">HTTP Headers</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full font-mono text-xs text-left">
                            <tbody>
                                @foreach ($_SERVER as $key => $value)
                                @if (str_starts_with($key, 'HTTP_'))
                                <tr class="border-b border-slate-800/40 hover:bg-slate-900/40">
                                    <td class="py-1.5 pr-4 text-slate-400 font-medium">{{ str_replace('HTTP_', '', $key) }}</td>
                                    <td class="py-1.5 text-slate-200 break-all">{{ is_array($value) ? json_encode($value) : $value }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Environment & Server -->
            <div id="tab-env" class="tab-content p-6 space-y-4 hidden">
                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-3 font-mono text-xs">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-500">NodeX Framework Version:</span>
                            <span class="text-indigo-400 font-bold">v{{ $nodeXVersion }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-500">PHP Version:</span>
                            <span class="text-slate-200">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-500">Server Software:</span>
                            <span class="text-slate-200">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI Server' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-500">Timezone:</span>
                            <span class="text-slate-200">{{ date_default_timezone_get() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="max-w-7xl mx-auto px-6 pt-10 text-center text-xs text-slate-600 font-mono">
        <p>NodeX Studio Exception Inspector • Thiết kế bởi NodeX Studio Engineering Team</p>
    </footer>

    <!-- Interactive Tabs & Clipboard Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Tab switching logic
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-tab');

                    tabButtons.forEach(b => {
                        b.classList.remove('text-white', 'border-indigo-500');
                        b.classList.add('text-slate-400', 'border-transparent');
                    });

                    btn.classList.add('text-white', 'border-indigo-500');
                    btn.classList.remove('text-slate-400', 'border-transparent');

                    tabContents.forEach(c => c.classList.add('hidden'));
                    document.getElementById(targetId)?.classList.remove('hidden');
                });
            });

            // Copy error message logic
            const copyBtn = document.getElementById('copyErrorBtn');
            copyBtn?.addEventListener('click', () => {
                const title = document.querySelector('h1')?.innerText || '';
                const fileInfo = document.querySelector('span.text-rose-400')?.innerText || '';
                const textToCopy = `${title}\nLocation: ${fileInfo}`;

                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<span class="text-emerald-400 font-bold">✔ Đã sao chép!</span>';
                    setTimeout(() => { copyBtn.innerHTML = originalText; }, 2000);
                });
            });
        });
    </script>
</body>
</html>
