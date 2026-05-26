@php
    $user = auth()->user();
    $isAdmin = $user->hasRole(['admin','owner']);
    $isStaff = $user->isStaff();
@endphp
<aside class="fixed inset-y-0 left-0 z-40 w-72 bg-forest-950 text-bone-100 transform transition-transform lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex flex-col h-full">
        <div class="px-7 py-7 border-b border-bone-100/10">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-11 h-11 border border-bone-100/30 flex items-center justify-center">
                    <x-icon name="mountain" class="w-5 h-5"/>
                </div>
                <div class="leading-none">
                    <div class="font-display text-xl font-semibold text-bone-50 tracking-super-tight">Sabana Tenda</div>
                    <div class="font-mono text-[10px] tracking-[0.2em] text-bone-400 uppercase mt-1.5">Rental Mgmt System</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-0.5 scrollbar-thin">
            @if ($isAdmin)
                <div class="px-3 pb-2 pt-1 font-mono text-[10px] tracking-[0.2em] text-bone-400 uppercase">Operations</div>
                @php
                    $adminLinks = [
                        ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'chart-bar', 'label' => 'Dashboard'],
                        ['route' => 'admin.inventory.index', 'pattern' => 'admin.inventory*', 'icon' => 'backpack', 'label' => 'Inventaris'],
                        ['route' => 'admin.categories.index', 'pattern' => 'admin.categories*', 'icon' => 'folder', 'label' => 'Kategori'],
                        ['route' => 'staff.rentals.index', 'pattern' => 'staff.rentals*', 'icon' => 'clipboard-list', 'label' => 'Peminjaman'],
                        ['route' => 'staff.returns.index', 'pattern' => 'staff.returns*', 'icon' => 'arrow-uturn-left', 'label' => 'Pengembalian'],
                        ['route' => 'admin.customers.index', 'pattern' => 'admin.customers*', 'icon' => 'users', 'label' => 'Pelanggan'],
                    ];
                @endphp
                @foreach ($adminLinks as $link)
                    <a href="{{ route($link['route']) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs($link['pattern']) ? 'bg-forest-800 text-bone-50 font-medium' : 'text-bone-200 hover:bg-forest-900 hover:text-bone-50' }}">
                        <x-icon :name="$link['icon']" class="w-4 h-4 {{ request()->routeIs($link['pattern']) ? 'text-ember-400' : '' }}"/>
                        {{ $link['label'] }}
                    </a>
                @endforeach

                <div class="px-3 pb-2 pt-5 font-mono text-[10px] tracking-[0.2em] text-bone-400 uppercase">Administration</div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.users*') ? 'bg-forest-800 text-bone-50 font-medium' : 'text-bone-200 hover:bg-forest-900 hover:text-bone-50' }}">
                    <x-icon name="shield" class="w-4 h-4 {{ request()->routeIs('admin.users*') ? 'text-ember-400' : '' }}"/>
                    User &amp; Akses
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('admin.reports*') ? 'bg-forest-800 text-bone-50 font-medium' : 'text-bone-200 hover:bg-forest-900 hover:text-bone-50' }}">
                    <x-icon name="trending-up" class="w-4 h-4 {{ request()->routeIs('admin.reports*') ? 'text-ember-400' : '' }}"/>
                    Laporan
                </a>
            @elseif ($isStaff)
                <div class="px-3 pb-2 pt-1 font-mono text-[10px] tracking-[0.2em] text-bone-400 uppercase">Operations</div>
                @php
                    $staffLinks = [
                        ['route' => 'staff.dashboard', 'pattern' => 'staff.dashboard', 'icon' => 'chart-bar', 'label' => 'Dashboard'],
                        ['route' => 'staff.inventory.index', 'pattern' => 'staff.inventory*', 'icon' => 'backpack', 'label' => 'Inventaris'],
                        ['route' => 'staff.rentals.index', 'pattern' => 'staff.rentals*', 'icon' => 'clipboard-list', 'label' => 'Peminjaman'],
                        ['route' => 'staff.returns.index', 'pattern' => 'staff.returns*', 'icon' => 'arrow-uturn-left', 'label' => 'Pengembalian'],
                        ['route' => 'staff.customers.index', 'pattern' => 'staff.customers*', 'icon' => 'users', 'label' => 'Pelanggan'],
                    ];
                @endphp
                @foreach ($staffLinks as $link)
                    <a href="{{ route($link['route']) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs($link['pattern']) ? 'bg-forest-800 text-bone-50 font-medium' : 'text-bone-200 hover:bg-forest-900 hover:text-bone-50' }}">
                        <x-icon :name="$link['icon']" class="w-4 h-4 {{ request()->routeIs($link['pattern']) ? 'text-ember-400' : '' }}"/>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            @else
                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('customer.dashboard') ? 'bg-forest-800 text-bone-50 font-medium' : 'text-bone-200 hover:bg-forest-900 hover:text-bone-50' }}">
                    <x-icon name="chart-bar" class="w-4 h-4 {{ request()->routeIs('customer.dashboard') ? 'text-ember-400' : '' }}"/>
                    Dashboard
                </a>
                <a href="{{ route('customer.booking') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm {{ request()->routeIs('customer.booking') ? 'bg-forest-800 text-bone-50 font-medium' : 'text-bone-200 hover:bg-forest-900 hover:text-bone-50' }}">
                    <x-icon name="shopping-bag" class="w-4 h-4 {{ request()->routeIs('customer.booking') ? 'text-ember-400' : '' }}"/>
                    Sewa Sekarang
                </a>
                <a href="{{ route('catalog') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-bone-200 hover:bg-forest-900 hover:text-bone-50">
                    <x-icon name="search" class="w-4 h-4"/>
                    Jelajahi Katalog
                </a>
            @endif
        </nav>

        <div class="border-t border-bone-100/10 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 border border-bone-100/30 flex items-center justify-center text-bone-50 font-display text-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-medium text-bone-50 truncate">{{ $user->name }}</div>
                    <div class="font-mono text-[10px] tracking-[0.15em] uppercase text-ember-400">{{ $user->roleLabel() }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-3 py-2.5 text-sm font-medium text-bone-200 hover:bg-forest-900 hover:text-bone-50 border border-bone-100/10 flex items-center gap-2 justify-center">
                    <x-icon name="log-out" class="w-4 h-4"/> Logout
                </button>
            </form>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-forest-950/60 z-30 lg:hidden"></div>
