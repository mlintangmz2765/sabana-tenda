@extends('layouts.dashboard')
@section('title', 'Dashboard Customer')
@section('page-title', 'Dashboard Saya')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-bone-200 border border-bone-200 mb-8">
    <div class="bg-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="tent" class="w-5 h-5 text-bone-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Sewa Aktif</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-forest-700 mt-3 leading-none ticker">{{ $stats['active'] }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">Sedang berjalan</div>
    </div>
    <div class="bg-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="check-circle" class="w-5 h-5 text-bone-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Selesai</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-forest-950 mt-3 leading-none ticker">{{ $stats['completed'] }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">Transaksi selesai</div>
    </div>
    <div class="bg-forest-950 text-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="credit-card" class="w-5 h-5 text-ember-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-300">Total Pengeluaran</div>
        <div class="font-display text-4xl font-medium tracking-super-tight mt-3 leading-none ticker">Rp{{ number_format($stats['total_spent'] / 1000, 0) }}<span class="text-ember-400 text-2xl">rb</span></div>
        <div class="text-xs text-bone-300 mt-2 font-mono">Sepanjang waktu</div>
    </div>
</div>

@if (! $customer)
    <div class="mb-6 border border-ember-600 bg-ember-300/30 px-5 py-4 text-ember-700 text-sm flex items-start gap-3">
        <x-icon name="info" class="w-5 h-5 mt-0.5"/>
        <div>
            <h3 class="font-semibold text-ember-700">Profil customer belum lengkap</h3>
            <p class="text-sm text-ember-700 mt-1">
                Lengkapi profil customer Anda dengan menghubungi staff SABANA Tenda untuk dapat membuat reservasi.
                Sementara ini, Anda dapat <a href="{{ route('catalog') }}" class="font-semibold underline">menelusuri katalog</a> dan menghubungi staff kami untuk proses sewa.
            </p>
        </div>
    </div>
@endif

<div class="card-stack overflow-hidden">
    <div class="p-6 lg:p-8 border-b border-bone-200 flex items-end justify-between">
        <div>
            <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Riwayat</div>
            <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight">Peminjaman Saya</div>
        </div>
        <a href="{{ route('catalog') }}" class="text-sm font-medium text-forest-950 hover:text-forest-700 inline-flex items-center gap-2 group">
            Sewa Lagi <x-icon name="arrow-up-right" class="w-3.5 h-3.5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
        </a>
    </div>
    @if ($rentals->isEmpty())
        <div class="text-center py-16 px-6">
            <x-icon name="tent" class="w-16 h-16 mx-auto mb-4 text-bone-300"/>
            <p class="text-bone-700 mb-4 font-display text-lg">Belum ada peminjaman. Yuk mulai petualanganmu!</p>
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-forest-950 text-bone-50 text-sm font-semibold hover:bg-forest-800 transition">
                <x-icon name="shopping-bag" class="w-4 h-4"/> Lihat Katalog
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 border-b border-bone-200">
                        <th class="py-3 px-6">Kode</th>
                        <th class="py-3 px-6">Barang</th>
                        <th class="py-3 px-6">Periode</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bone-200">
                    @foreach ($rentals as $rental)
                        <tr class="hover:bg-bone-50">
                            <td class="py-4 px-6 font-mono text-xs text-bone-700">{{ $rental->rental_code }}</td>
                            <td class="py-4 px-6 text-bone-700 max-w-xs truncate">{{ $rental->details->pluck('item.name')->join(', ') }}</td>
                            <td class="py-4 px-6 text-bone-700 text-xs font-mono">
                                {{ $rental->rental_date->format('d M') }} &rarr; {{ $rental->return_date->format('d M Y') }}
                                <div class="text-bone-500">{{ $rental->duration_days }} hari</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium font-mono tracking-wider uppercase {{ $rental->statusBadgeClass() }}">
                                    {{ $rental->statusLabel() }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-forest-950 ticker">Rp{{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
