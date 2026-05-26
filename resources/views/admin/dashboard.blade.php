@extends('layouts.dashboard')
@section('title', 'Dashboard Owner')
@section('page-title', 'Overview')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-bone-200 border border-bone-200 mb-8">
    <div class="bg-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="box" class="w-5 h-5 text-bone-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Total Barang</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-forest-950 mt-3 leading-none ticker">{{ number_format($stats['total_items']) }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">{{ $stats['total_items_unique'] }} jenis produk</div>
    </div>
    <div class="bg-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="tent" class="w-5 h-5 text-bone-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Sedang Disewa</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-forest-700 mt-3 leading-none ticker">{{ number_format($stats['items_rented']) }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">{{ $stats['items_available'] }} unit tersedia</div>
    </div>
    <div class="bg-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="clock" class="w-5 h-5 text-bone-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Terlambat</div>
        <div class="font-display text-5xl font-medium tracking-super-tight text-ember-600 mt-3 leading-none ticker">{{ number_format($stats['late_returns']) }}</div>
        <div class="text-xs text-bone-700 mt-2 font-mono">Perlu ditindaklanjuti</div>
    </div>
    <div class="bg-forest-950 text-bone-50 p-6 relative">
        <div class="absolute top-4 right-4"><x-icon name="trending-up" class="w-5 h-5 text-ember-400"/></div>
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-300">Pendapatan</div>
        <div class="font-display text-4xl font-medium tracking-super-tight mt-3 leading-none ticker">Rp{{ number_format($stats['revenue_this_month'] / 1000000, 1) }}<span class="text-ember-400 text-2xl">jt</span></div>
        <div class="text-xs text-bone-300 mt-2 font-mono">{{ $stats['transactions_this_month'] }} transaksi bulan ini</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 card-stack p-6 lg:p-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Tren Peminjaman</div>
                <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight">30 hari terakhir</div>
            </div>
        </div>
        <div class="h-64"><canvas id="trendChart"></canvas></div>
    </div>

    <div class="card-stack p-6 lg:p-8">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Terpopuler</div>
        <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight mb-6">Barang sering disewa</div>
        @php
            $itemIcons = ['Tenda' => 'tent', 'Carrier & Tas' => 'backpack', 'Sleeping Gear' => 'sleeping-bag', 'Alat Masak' => 'flame', 'Lighting' => 'lantern', 'Perlengkapan Lain' => 'compass'];
        @endphp
        <div class="space-y-4">
            @forelse ($popularItems as $idx => $item)
                <div class="flex items-center gap-4">
                    <div class="font-mono text-xs text-bone-400 w-6">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="w-10 h-10 bg-bone-100 flex items-center justify-center">
                        <x-icon :name="$itemIcons[$item->category->name ?? ''] ?? 'mountain'" class="w-5 h-5 text-forest-700"/>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-forest-950 truncate">{{ $item->name }}</div>
                        <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700">{{ $item->rented_count }}&times; disewa</div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-bone-500 text-center py-4">Belum ada data</div>
            @endforelse
        </div>
    </div>
</div>

<div class="card-stack overflow-hidden">
    <div class="p-6 lg:p-8 border-b border-bone-200 flex items-end justify-between">
        <div>
            <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Aktivitas Terbaru</div>
            <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight">Transaksi terakhir</div>
        </div>
        <a href="{{ route('staff.rentals.index') }}" class="text-sm font-medium text-forest-950 hover:text-forest-700 inline-flex items-center gap-2 group">
            Semua transaksi <x-icon name="arrow-up-right" class="w-3.5 h-3.5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 border-b border-bone-200">
                    <th class="py-3 px-6">Kode</th>
                    <th class="py-3 px-6">Customer</th>
                    <th class="py-3 px-6">Item</th>
                    <th class="py-3 px-6">Periode</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-bone-200">
                @foreach ($recentRentals as $rental)
                    <tr class="hover:bg-bone-50">
                        <td class="py-4 px-6 font-mono text-xs text-bone-700">{{ $rental->rental_code }}</td>
                        <td class="py-4 px-6 font-medium text-forest-950">{{ $rental->customer->name }}</td>
                        <td class="py-4 px-6 text-bone-700 truncate max-w-xs">{{ $rental->details->pluck('item.name')->join(', ') }}</td>
                        <td class="py-4 px-6 text-bone-700 text-xs font-mono">{{ $rental->rental_date->format('d M') }} &rarr; {{ $rental->return_date->format('d M') }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium font-mono tracking-wider uppercase {{ $rental->statusBadgeClass() }}">{{ $rental->statusLabel() }}</span>
                        </td>
                        <td class="py-4 px-6 text-right font-semibold text-forest-950 ticker">Rp{{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const trendData = @json($rentalTrend);
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendData.map(d => new Date(d.date).toLocaleDateString('id-ID', {month: 'short', day: 'numeric'})),
            datasets: [{
                label: 'Transaksi',
                data: trendData.map(d => d.count),
                borderColor: '#0f2e22',
                backgroundColor: 'rgba(58, 148, 98, 0.08)',
                borderWidth: 1.5,
                fill: true,
                tension: 0.35,
                pointRadius: 2,
                pointBackgroundColor: '#d97706',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, font: { family: 'JetBrains Mono', size: 10 } }, grid: { color: '#e6dfd0' } },
                x: { ticks: { font: { family: 'JetBrains Mono', size: 10 } }, grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection
