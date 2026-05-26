@extends('layouts.dashboard')
@section('title', 'Laporan')
@section('page-title', 'Laporan & Analytics')

@section('content')
<div class="bg-white border border-slate-200 rounded-xl p-5 mb-6">
    <form method="GET" class="grid md:grid-cols-5 gap-3 items-end">
        <div>
            <label class="text-xs text-slate-500 uppercase font-semibold">Periode</label>
            <select name="period" onchange="this.form.submit()" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                <option value="today" @selected($period==='today')>Hari Ini</option>
                <option value="this_week" @selected($period==='this_week')>Minggu Ini</option>
                <option value="this_month" @selected($period==='this_month')>Bulan Ini</option>
                <option value="last_month" @selected($period==='last_month')>Bulan Lalu</option>
                <option value="this_year" @selected($period==='this_year')>Tahun Ini</option>
                <option value="custom" @selected($period==='custom')>Custom</option>
            </select>
        </div>
        @if ($period === 'custom')
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm mt-5">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm mt-5">
        @endif
        <div class="text-sm text-slate-600 mt-5">
            <strong>{{ $start->translatedFormat('d M Y') }}</strong> – <strong>{{ $end->translatedFormat('d M Y') }}</strong>
        </div>
        <a href="{{ route('admin.reports.export', request()->all()) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium mt-5 text-center inline-flex items-center gap-1.5"><x-icon name="download" class="w-4 h-4"/> Ekspor CSV</a>
    </form>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-sabana-700 to-sabana-800 text-white rounded-xl p-5">
        <div class="text-xs uppercase opacity-80 font-semibold">Total Pendapatan</div>
        <div class="text-2xl font-bold mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-xs text-slate-500 uppercase font-semibold">Total Transaksi</div>
        <div class="text-3xl font-bold mt-2 text-slate-900">{{ $stats['total_rentals'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-xs text-slate-500 uppercase font-semibold">Rata-rata/Transaksi</div>
        <div class="text-2xl font-bold mt-2 text-slate-900">Rp {{ number_format($stats['avg_per_transaction'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-xs text-slate-500 uppercase font-semibold">Pelanggan Unik</div>
        <div class="text-3xl font-bold mt-2 text-emerald-600">{{ $stats['unique_customers'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-base font-semibold text-slate-900 mb-4">Pendapatan Harian</div>
        <div class="h-64"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-base font-semibold text-slate-900 mb-4">Pendapatan per Kategori</div>
        <div class="h-64"><canvas id="categoryChart"></canvas></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-base font-semibold text-slate-900 mb-4">Top 10 Barang</div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase border-b border-slate-200">
                    <th class="py-2 pr-4">Barang</th>
                    <th class="py-2 pr-4 text-center">Disewa</th>
                    <th class="py-2 pr-4 text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($topItems as $item)
                    <tr>
                        <td class="py-3 pr-4 font-medium">{{ $item->name }}</td>
                        <td class="py-3 pr-4 text-center">{{ $item->total_rented }}</td>
                        <td class="py-3 pr-4 text-right font-semibold">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-6 text-slate-500">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-base font-semibold text-slate-900 mb-4">Laporan Kerusakan</div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase border-b border-slate-200">
                    <th class="py-2 pr-4">Barang</th>
                    <th class="py-2 pr-4 text-center">Insiden</th>
                    <th class="py-2 pr-4 text-right">Biaya Perbaikan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($damageReport as $damage)
                    <tr>
                        <td class="py-3 pr-4 font-medium">{{ $damage->name }}</td>
                        <td class="py-3 pr-4 text-center">{{ $damage->damage_count }}</td>
                        <td class="py-3 pr-4 text-right font-semibold text-rose-600">Rp {{ number_format($damage->total_repair, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-6 text-slate-500 inline-flex items-center gap-1">Tidak ada kerusakan <x-icon name="check-circle" class="w-4 h-4 text-emerald-600"/></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const revData = @json($revenueOverTime);
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revData.map(d => new Date(d.date).toLocaleDateString('id-ID', {day:'numeric', month:'short'})),
            datasets: [{
                label: 'Pendapatan',
                data: revData.map(d => d.revenue),
                backgroundColor: '#15803d',
                borderRadius: 4,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    const catData = @json($categoryBreakdown);
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catData.map(c => c.name),
            datasets: [{
                data: catData.map(c => c.revenue),
                backgroundColor: ['#15803d','#22c55e','#86efac','#bbf7d0','#16a34a','#166534'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
    });
</script>
@endpush
@endsection
