@php $user = auth()->user(); @endphp
<header class="sticky top-0 z-20 bg-bone-50/85 backdrop-blur-md border-b border-bone-200 px-5 sm:px-8 lg:px-10 h-20 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 hover:bg-bone-100 text-forest-950">
            <x-icon name="menu" class="w-5 h-5"/>
        </button>
        <div>
            <div class="text-xs text-bone-700 flex items-center gap-2">
                <x-icon name="hand-wave" class="w-3.5 h-3.5"/>
                Halo, {{ explode(' ', $user->name)[0] }}
            </div>
            <div class="font-display text-2xl font-semibold text-forest-950 tracking-super-tight">@yield('page-title', 'Dashboard')</div>
        </div>
    </div>
    <div class="hidden md:flex items-center gap-5">
        <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-bone-700 hover:text-forest-950">
            <x-icon name="home" class="w-4 h-4"/> Website
        </a>
        <div class="text-right border-l border-bone-200 pl-5">
            <div class="font-mono text-[10px] tracking-[0.15em] uppercase text-bone-500">{{ now()->translatedFormat('l') }}</div>
            <div class="text-sm font-semibold text-forest-950">{{ now()->translatedFormat('d M Y') }}</div>
        </div>
    </div>
</header>
