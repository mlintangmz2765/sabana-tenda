<nav class="bg-bone-50/90 border-b border-bone-200 sticky top-0 z-40 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 border border-forest-900 flex items-center justify-center text-forest-950 group-hover:bg-forest-950 group-hover:text-bone-50 transition">
                        <x-icon name="mountain" class="w-5 h-5"/>
                    </div>
                    <div class="leading-none">
                        <div class="font-display text-xl font-semibold tracking-super-tight text-forest-950">Sabana</div>
                        <div class="font-mono text-[10px] tracking-[0.2em] text-bone-700 uppercase mt-1">Tenda &middot; Est. 2020</div>
                    </div>
                </a>
                <div class="hidden md:flex items-center gap-7 text-sm">
                    <a href="{{ route('home') }}" class="font-medium relative {{ request()->routeIs('home') ? 'text-forest-950' : 'text-bone-700 hover:text-forest-950' }}">
                        Beranda
                        @if(request()->routeIs('home'))<span class="absolute -bottom-1 left-0 right-0 h-px bg-forest-950"></span>@endif
                    </a>
                    <a href="{{ route('catalog') }}" class="font-medium relative {{ request()->routeIs('catalog*') ? 'text-forest-950' : 'text-bone-700 hover:text-forest-950' }}">
                        Katalog
                        @if(request()->routeIs('catalog*'))<span class="absolute -bottom-1 left-0 right-0 h-px bg-forest-950"></span>@endif
                    </a>
                    <a href="{{ route('about') }}" class="font-medium relative {{ request()->routeIs('about') ? 'text-forest-950' : 'text-bone-700 hover:text-forest-950' }}">
                        Tentang
                        @if(request()->routeIs('about'))<span class="absolute -bottom-1 left-0 right-0 h-px bg-forest-950"></span>@endif
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ auth()->user()->isCustomer() ? route('customer.dashboard') : (auth()->user()->isStaff() ? route('staff.dashboard') : route('admin.dashboard')) }}"
                       class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-forest-950 hover:text-forest-700">
                        Dashboard
                        <x-icon name="arrow-up-right" class="w-4 h-4"/>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-bone-700 hover:text-ember-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-forest-950 hover:text-forest-700">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium transition">
                        Daftar
                        <x-icon name="arrow-right" class="w-4 h-4"/>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
