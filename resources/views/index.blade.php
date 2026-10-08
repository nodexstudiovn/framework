@extends('layouts.app')

@section('title', 'Nguyễn Anh Kiệt — Software Engineer & UI/UX Designer')

@section('content')
<!-- Hero Section -->
<section class="glass-card rounded-2xl overflow-hidden border border-zinc-800/80 relative">
    <!-- Minimalist Geometric Cover Banner -->
    <div class="h-44 sm:h-52 w-full bg-zinc-900 relative overflow-hidden flex items-center justify-end px-8">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3f3f46_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <!-- Subtle diagonal lines grid effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-900/40 to-transparent"></div>
        <div class="relative z-10 font-mono text-[11px] text-zinc-400 tracking-widest uppercase">
            SYSTEM_ARCHITECT // 2026.VER
        </div>
    </div>

    <!-- Profile Info Bar -->
    <div class="px-6 sm:px-8 pb-8 pt-0 relative">
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between -mt-16 sm:-mt-20 gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-end space-y-4 sm:space-y-0 sm:space-x-5">
                <div class="relative">
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl p-1 bg-zinc-900 border border-zinc-700 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=400" alt="Nguyễn Anh Kiệt" class="w-full h-full object-cover rounded-xl grayscale hover:grayscale-0 transition-all duration-300">
                    </div>
                    <div class="absolute bottom-1 right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-zinc-950"></div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-zinc-100">Nguyễn Anh Kiệt</h1>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-zinc-800 border border-zinc-700 text-zinc-300">PRO</span>
                    </div>
                    <p class="text-xs sm:text-sm text-zinc-400 font-medium">Software Engineer & UI/UX Designer</p>
                    <p class="text-xs text-zinc-400 flex items-center space-x-1 pt-0.5 font-mono">
                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Ho Chi Minh City, Vietnam</span>
                    </p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center space-x-2.5 w-full sm:w-auto">
                <button @click="activeTab = 'contact'" class="flex-1 sm:flex-none px-4 py-2 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-900 font-medium text-xs transition-colors flex items-center justify-center space-x-1.5">
                    <span>Get in Touch</span>
                </button>
                <button @click="navigator.clipboard.writeText('kiet.nguyen@nodex.studio'); copyToast = true" class="p-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-zinc-300 transition-colors" title="Copy Email">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                </button>
                <button @click="alert('Downloading CV PDF...')" class="p-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-zinc-300 transition-colors" title="Download CV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- TAB 1: OVERVIEW -->
<div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <!-- Metrics Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-5 rounded-xl border border-zinc-800 space-y-1">
            <div class="text-zinc-400 font-mono text-[11px] uppercase tracking-wider">Experience</div>
            <div class="text-xl font-semibold text-zinc-100">7+ Years</div>
        </div>
        <div class="glass-card p-5 rounded-xl border border-zinc-800 space-y-1">
            <div class="text-zinc-400 font-mono text-[11px] uppercase tracking-wider">Projects Shipped</div>
            <div class="text-xl font-semibold text-zinc-100">40+</div>
        </div>
        <div class="glass-card p-5 rounded-xl border border-zinc-800 space-y-1">
            <div class="text-zinc-400 font-mono text-[11px] uppercase tracking-wider">Code Commits</div>
            <div class="text-xl font-semibold text-zinc-100">3,400+</div>
        </div>
        <div class="glass-card p-5 rounded-xl border border-zinc-800 space-y-1">
            <div class="text-zinc-400 font-mono text-[11px] uppercase tracking-wider">Uptime SLA</div>
            <div class="text-xl font-semibold text-zinc-100">99.99%</div>
        </div>
    </div>

    <!-- Bio & Tech Stack Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Bio -->
        <div class="lg:col-span-2 glass-card p-6 sm:p-8 rounded-2xl border border-zinc-800 space-y-4">
            <h2 class="text-sm font-mono text-zinc-400 uppercase tracking-widest">ABOUT_ME</h2>
            <p class="text-sm sm:text-base text-zinc-300 leading-relaxed font-normal">
                I'm <strong class="text-zinc-100 font-semibold">Nguyễn Anh Kiệt</strong>, a multidisciplinary software engineer and UI/UX designer focused on building high-performance SaaS applications and developer tooling. 
            </p>
            <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed">
                My work bridges deep backend architecture (Laravel, NestJS, PostgreSQL, Redis) with ultra-refined frontend design systems inspired by Vercel and Linear. I care deeply about typographic hierarchy, micro-interactions, and flawless developer experience.
            </p>

            <!-- Tech Badges -->
            <div class="pt-4 space-y-3">
                <div class="text-xs font-mono text-zinc-400 uppercase tracking-wider">Primary Stack</div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Laravel</span>
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">NestJS</span>
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">TypeScript</span>
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">React / Next.js</span>
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Tailwind CSS</span>
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">PostgreSQL</span>
                    <span class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-mono">Redis</span>
                </div>
            </div>
        </div>

        <!-- Current Focus -->
        <div class="glass-card p-6 sm:p-8 rounded-2xl border border-zinc-800 space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <h2 class="text-sm font-mono text-zinc-400 uppercase tracking-widest">CURRENT_FOCUS</h2>
                <ul class="space-y-3 text-xs sm:text-sm text-zinc-300">
                    <li class="flex items-start space-x-2">
                        <span class="text-zinc-400 font-mono">01.</span>
                        <span>Designing multi-tenant SaaS database sharding patterns.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-zinc-400 font-mono">02.</span>
                        <span>Building high-speed UI component libraries with Tailwind.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-zinc-400 font-mono">03.</span>
                        <span>AI wrapper integrations & vector search pipelines.</span>
                    </li>
                </ul>
            </div>
            <div class="pt-4 border-t border-zinc-800/80">
                <span class="text-[11px] font-mono text-emerald-400 flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Status: Open for Q2/Q3 Contracts</span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- TAB 2: PROJECTS -->
<div x-show="activeTab === 'projects'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-zinc-100 tracking-tight">Featured SaaS Works</h2>
            <p class="text-xs text-zinc-400">Production software platforms engineered for scalability and speed.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Project 1 -->
        <div class="glass-card rounded-2xl p-6 border border-zinc-800 space-y-4 flex flex-col justify-between group hover:border-zinc-700 transition-all">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono bg-zinc-900 border border-zinc-800 text-zinc-300">SaaS Platform</span>
                    <span class="text-[11px] font-mono text-emerald-400">● Live v2.4</span>
                </div>
                <h3 class="text-base font-semibold text-zinc-100 group-hover:text-white">KarPlay Analytics Engine</h3>
                <p class="text-xs text-zinc-400 leading-relaxed">High-throughput telemetry and real-time event processing dashboard built for multi-tenant enterprise customers.</p>
                <div class="flex flex-wrap gap-1.5 pt-2">
                    <span class="px-2 py-0.5 rounded bg-zinc-900 text-zinc-300 text-[10px] font-mono">Next.js</span>
                    <span class="px-2 py-0.5 rounded bg-zinc-900 text-zinc-300 text-[10px] font-mono">NestJS</span>
                    <span class="px-2 py-0.5 rounded bg-zinc-900 text-zinc-300 text-[10px] font-mono">PostgreSQL</span>
                </div>
            </div>
            <div class="pt-4 border-t border-zinc-800/80 flex items-center justify-between text-xs">
                <a href="#" class="text-zinc-300 hover:text-white font-medium flex items-center space-x-1"><span>View Demo</span><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                <a href="#" class="text-zinc-500 hover:text-zinc-300 font-mono">GitHub ↗</a>
            </div>
        </div>

        <!-- Project 2 -->
        <div class="glass-card rounded-2xl p-6 border border-zinc-800 space-y-4 flex flex-col justify-between group hover:border-zinc-700 transition-all">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono bg-zinc-900 border border-zinc-800 text-zinc-300">Developer Tool</span>
                    <span class="text-[11px] font-mono text-emerald-400">● Live v1.0</span>
                </div>
                <h3 class="text-base font-semibold text-zinc-100 group-hover:text-white">NodeX SMM Orchestrator</h3>
                <p class="text-xs text-zinc-400 leading-relaxed">Automated API routing and queue worker management system handling over 2M daily operations.</p>
                <div class="flex flex-wrap gap-1.5 pt-2">
                    <span class="px-2 py-0.5 rounded bg-zinc-900 text-zinc-300 text-[10px] font-mono">Laravel</span>
                    <span class="px-2 py-0.5 rounded bg-zinc-900 text-zinc-300 text-[10px] font-mono">Redis</span>
                    <span class="px-2 py-0.5 rounded bg-zinc-900 text-zinc-300 text-[10px] font-mono">Tailwind</span>
                </div>
            </div>
            <div class="pt-4 border-t border-zinc-800/80 flex items-center justify-between text-xs">
                <a href="#" class="text-zinc-300 hover:text-white font-medium flex items-center space-x-1"><span>View Demo</span><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                <a href="#" class="text-zinc-500 hover:text-zinc-300 font-mono">GitHub ↗</a>
            </div>
        </div>
    </div>
</div>

<!-- TAB 3: EXPERIENCE -->
<div x-show="activeTab === 'experience'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
    <div>
        <h2 class="text-lg font-semibold text-zinc-100 tracking-tight">Experience & Education</h2>
        <p class="text-xs text-zinc-400">Career progression and academic milestones.</p>
    </div>

    <div class="glass-card rounded-2xl p-6 sm:p-8 border border-zinc-800 space-y-8">
        <div class="space-y-6 border-l border-zinc-800 pl-6 ml-2">
            <div class="relative space-y-1">
                <span class="absolute -left-[31px] top-1.5 w-2.5 h-2.5 rounded-full bg-zinc-400 ring-4 ring-zinc-950"></span>
                <span class="text-[11px] font-mono text-zinc-400">2022 — PRESENT</span>
                <h3 class="text-sm font-semibold text-zinc-100">Lead Full-Stack Architect</h3>
                <span class="text-xs text-zinc-400 block font-mono">NodeX Studio • Ho Chi Minh City</span>
                <p class="text-xs text-zinc-300 pt-2 leading-relaxed">Directing technical architecture for enterprise SaaS products. Leading a team of 6 engineers building microservices with NestJS and React.</p>
            </div>

            <div class="relative space-y-1">
                <span class="absolute -left-[31px] top-1.5 w-2.5 h-2.5 rounded-full bg-zinc-700 ring-4 ring-zinc-950"></span>
                <span class="text-[11px] font-mono text-zinc-400">2019 — 2022</span>
                <h3 class="text-sm font-semibold text-zinc-100">Senior Backend Engineer</h3>
                <span class="text-xs text-zinc-400 block font-mono">VinaTech Solutions</span>
                <p class="text-xs text-zinc-300 pt-2 leading-relaxed">Optimized legacy PHP monoliths into event-driven microservices. Implemented Redis caching layers reducing DB load by 50%.</p>
            </div>

            <div class="relative space-y-1">
                <span class="absolute -left-[31px] top-1.5 w-2.5 h-2.5 rounded-full bg-zinc-700 ring-4 ring-zinc-950"></span>
                <span class="text-[11px] font-mono text-zinc-400">2015 — 2019</span>
                <h3 class="text-sm font-semibold text-zinc-100">B.S. in Computer Science</h3>
                <span class="text-xs text-zinc-400 block font-mono">University of Technology (HCMUT)</span>
                <p class="text-xs text-zinc-300 pt-2 leading-relaxed">Graduated with Honors. Focused on Distributed Systems and Human-Computer Interaction (HCI).</p>
            </div>
        </div>
    </div>
</div>

<!-- TAB 4: CONTACT -->
<div x-show="activeTab === 'contact'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
    <div>
        <h2 class="text-lg font-semibold text-zinc-100 tracking-tight">Get in Touch</h2>
        <p class="text-xs text-zinc-400">Have a project in mind or want to discuss architecture? Send a message.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Contact info cards -->
        <div class="space-y-3">
            <div class="glass-card p-4 rounded-xl border border-zinc-800 space-y-1">
                <span class="text-[10px] font-mono text-zinc-400 uppercase">Direct Email</span>
                <div class="text-xs font-semibold text-zinc-100 font-mono">kiet.nguyen@nodex.studio</div>
            </div>
            <div class="glass-card p-4 rounded-xl border border-zinc-800 space-y-1">
                <span class="text-[10px] font-mono text-zinc-400 uppercase">Telegram / Signal</span>
                <div class="text-xs font-semibold text-zinc-100 font-mono">@anhkiet_dev</div>
            </div>
            <div class="glass-card p-4 rounded-xl border border-zinc-800 space-y-1">
                <span class="text-[10px] font-mono text-zinc-400 uppercase">GitHub Profile</span>
                <div class="text-xs font-semibold text-zinc-100 font-mono">github.com/anhkiet-dev</div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="md:col-span-2 glass-card rounded-2xl p-6 sm:p-8 border border-zinc-800">
            <form onsubmit="alert('Message sent successfully! I will get back to you shortly.'); return false;" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-mono text-zinc-400 uppercase">Your Name</label>
                        <input type="text" required class="w-full px-3.5 py-2.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-600 transition-colors" placeholder="John Doe">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-mono text-zinc-400 uppercase">Email Address</label>
                        <input type="email" required class="w-full px-3.5 py-2.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-600 transition-colors" placeholder="john@company.com">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[11px] font-mono text-zinc-400 uppercase">Project Details / Message</label>
                    <textarea rows="4" required class="w-full px-3.5 py-2.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-600 transition-colors" placeholder="Tell me about your SaaS project or technical scope..."></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-900 text-xs font-semibold transition-colors">
                    Send Transmission
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
