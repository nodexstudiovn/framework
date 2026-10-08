<!-- Sticky Vercel/Linear Style Navbar -->
<header class="sticky top-0 z-50 glass-nav border-b border-zinc-800/80">
    <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
        <!-- Brand Monogram -->
        <a href="/" class="flex items-center space-x-3 group">
            <div class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-700 flex items-center justify-center text-zinc-200 font-mono text-xs font-semibold group-hover:border-zinc-500 transition-colors">
                NK
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold tracking-tight text-zinc-100">Nguyễn Anh Kiệt</span>
                <span class="text-[10px] text-zinc-400 font-mono tracking-wider">ENGINEER & DESIGNER</span>
            </div>
        </a>

        <!-- Desktop Tabs -->
        <nav class="hidden md:flex items-center space-x-1 bg-zinc-900/80 p-1 rounded-full border border-zinc-800">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-zinc-800 text-zinc-100 shadow-sm' : 'text-zinc-400 hover:text-zinc-200'" class="px-4 py-1.5 rounded-full text-xs font-medium transition-all">Overview</button>
            <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'bg-zinc-800 text-zinc-100 shadow-sm' : 'text-zinc-400 hover:text-zinc-200'" class="px-4 py-1.5 rounded-full text-xs font-medium transition-all">Projects</button>
            <button @click="activeTab = 'experience'" :class="activeTab === 'experience' ? 'bg-zinc-800 text-zinc-100 shadow-sm' : 'text-zinc-400 hover:text-zinc-200'" class="px-4 py-1.5 rounded-full text-xs font-medium transition-all">Experience</button>
            <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-zinc-800 text-zinc-100 shadow-sm' : 'text-zinc-400 hover:text-zinc-200'" class="px-4 py-1.5 rounded-full text-xs font-medium transition-all">Contact</button>
        </nav>

        <!-- Right Actions & Availability -->
        <div class="flex items-center space-x-4">
            <div class="hidden sm:flex items-center space-x-2 px-3 py-1 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-400 text-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="font-mono text-[11px]">Available for hire</span>
            </div>

            <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" @click.away="mobileMenu = false" class="md:hidden glass-card border-t border-zinc-800 px-6 py-4 space-y-2">
        <button @click="activeTab = 'overview'; mobileMenu = false" class="block w-full text-left py-2 text-sm text-zinc-300">Overview</button>
        <button @click="activeTab = 'projects'; mobileMenu = false" class="block w-full text-left py-2 text-sm text-zinc-300">Projects</button>
        <button @click="activeTab = 'experience'; mobileMenu = false" class="block w-full text-left py-2 text-sm text-zinc-300">Experience</button>
        <button @click="activeTab = 'contact'; mobileMenu = false" class="block w-full text-left py-2 text-sm text-zinc-300">Contact</button>
    </div>
</header>
<?php /**PATH C:\Users\Hi\Downloads\thongtincanhan\resources\views/partials/navbar.blade.php ENDPATH**/ ?>