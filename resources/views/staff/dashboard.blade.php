@extends('layouts.dashboard')
@section('title', 'Dashboard Staff')
@section('page-title', 'Overview Staff')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-bone-200 border border-bone-200 mb-8">
    <div class="bg-bone-50 p-6">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Sewa Aktif</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-forest-700 mt-3 leading-none ticker">{{ $stats['active_rentals'] }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">Sedang berjalan</div>
    </div>
    <div class="bg-bone-50 p-6">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Return Hari Ini</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-forest-950 mt-3 leading-none ticker">{{ $stats['returns_today'] }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">Jadwal kembali</div>
    </div>
    <div class="bg-bone-50 p-6">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Terlambat</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-ember-600 mt-3 leading-none ticker">{{ $stats['late_rentals'] }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">Perlu follow-up</div>
    </div>
    <div class="bg-forest-950 text-bone-50 p-6">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-300">Stok Tersedia</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-ember-400 mt-3 leading-none ticker">{{ $stats['items_available'] }}</div>
        <div class="text-xs text-bone-300 mt-2 font-mono">Unit siap sewa</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="card-stack p-6 lg:p-8">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Aksi Cepat</div>
        <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight mb-6">Mulai transaksi baru</div>
        <div class="grid grid-cols-2 gap-px bg-bone-200 border border-bone-200">
            <a href="{{ route('staff.rentals.create') }}" class="bg-bone-50 p-5 hover:bg-forest-950 hover:text-bone-50 transition group">
                <x-icon name="clipboard-list" class="w-8 h-8 mb-3 group-hover:text-ember-400"/>
                <div class="font-display text-lg font-medium tracking-super-tight">Buat Sewa</div>
                <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700 group-hover:text-bone-300 mt-1">Transaksi baru</div>
            </a>
            <a href="{{ route('staff.returns.create') }}" class="bg-bone-50 p-5 hover:bg-forest-950 hover:text-bone-50 transition group">
                <x-icon name="arrow-uturn-left" class="w-8 h-8 mb-3 group-hover:text-ember-400"/>
                <div class="font-display text-lg font-medium tracking-super-tight">Proses Return</div>
                <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700 group-hover:text-bone-300 mt-1">Catat pengembalian</div>
            </a>
            <a href="{{ route('staff.inventory.index') }}" class="bg-bone-50 p-5 hover:bg-forest-950 hover:text-bone-50 transition group">
                <x-icon name="backpack" class="w-8 h-8 mb-3 group-hover:text-ember-400"/>
                <div class="font-display text-lg font-medium tracking-super-tight">Cek Stok</div>
                <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700 group-hover:text-bone-300 mt-1">Inventaris</div>
            </a>
            <a href="{{ route('staff.customers.index') }}" class="bg-bone-50 p-5 hover:bg-forest-950 hover:text-bone-50 transition group">
                <x-icon name="users" class="w-8 h-8 mb-3 group-hover:text-ember-400"/>
                <div class="font-display text-lg font-medium tracking-super-tight">Pelanggan</div>
                <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700 group-hover:text-bone-300 mt-1">Database customer</div>
            </a>
        </div>
    </div>

    <div class="card-stack p-6 lg:p-8">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Jadwal Return</div>
        <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight mb-6">Mendekati hari ini</div>
        <div class="space-y-3 max-h-72 overflow-y-auto scrollbar-thin">
            @forelse ($todayReturns as $rental)
                <div class="flex items-center gap-4 p-3 border border-bone-200 hover:bg-bone-50 transition">
                    <div class="w-10 h-10 border border-forest-950 flex items-center justify-center font-display text-forest-950">
                        {{ strtoupper(substr($rental->customer->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-forest-950 truncate">{{ $rental->customer->name }}</div>
                        <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700">{{ $rental->rental_code }} &middot; {{ $rental->return_date->format('d M') }}</div>
                    </div>
                    @if ($rental->return_date->isPast())
                        <span class="px-2 py-0.5 bg-ember-300/40 text-ember-700 font-mono text-[9px] tracking-wider uppercase">Telat</span>
                    @endif
                    <a href="{{ route('staff.rentals.show', $rental) }}" class="text-forest-950 hover:text-forest-700"><x-icon name="arrow-up-right" class="w-4 h-4"/></a>
                </div>
            @empty
                <p class="text-sm text-bone-500 text-center py-6">Tidak ada return mendekat</p>
            @endforelse
        </div>
    </div>
</div>

<div class="card-stack overflow-hidden">
    <div class="p-6 lg:p-8 border-b border-bone-200 flex items-end justify-between">
        <div>
            <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Aktivitas</div>
            <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight">Transaksi terbaru</div>
        </div>
        <a href="{{ route('staff.rentals.index') }}" class="text-sm font-medium text-forest-950 hover:text-forest-700 inline-flex items-center gap-2 group">
            Semua <x-icon name="arrow-up-right" class="w-3.5 h-3.5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
        </a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 border-b border-bone-200">
                <th class="py-3 px-6">Kode</th>
                <th class="py-3 px-6">Customer</th>
                <th class="py-3 px-6">Staff</th>
                <th class="py-3 px-6">Tanggal</th>
                <th class="py-3 px-6">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-bone-200">
            @foreach ($recentRentals as $rental)
                <tr class="hover:bg-bone-50">
                    <td class="py-4 px-6">
                        <a href="{{ route('staff.rentals.show', $rental) }}" class="font-mono text-xs text-forest-700 hover:underline">{{ $rental->rental_code }}</a>
                    </td>
                    <td class="py-4 px-6 font-medium text-forest-950">{{ $rental->customer->name }}</td>
                    <td class="py-4 px-6 text-bone-700">{{ $rental->staff?->name ?? '—' }}</td>
                    <td class="py-4 px-6 text-bone-700 font-mono text-xs">{{ $rental->rental_date->format('d M Y') }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-mono tracking-wider uppercase {{ $rental->statusBadgeClass() }}">{{ $rental->statusLabel() }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
