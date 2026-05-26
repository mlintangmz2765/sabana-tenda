@extends('layouts.dashboard')
@section('title', 'Pengembalian')
@section('page-title', 'Pengembalian Barang')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="text-base font-semibold text-slate-900">Sewa Aktif & Terlambat</div>
            <a href="{{ route('staff.returns.create') }}" class="px-3 py-1.5 bg-sabana-700 text-white text-xs font-semibold rounded-lg">+ Proses Return</a>
        </div>
        <div class="space-y-2 max-h-96 overflow-y-auto">
            @forelse ($pendingReturns as $rental)
                <div class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50">
                    <div class="w-10 h-10 rounded-lg bg-sabana-100 text-sabana-700 flex items-center justify-center font-bold">
                        {{ strtoupper(substr($rental->customer->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-900">{{ $rental->customer->name }}</div>
                        <div class="text-xs text-slate-500">{{ $rental->rental_code }} · {{ $rental->details->count() }} item · Kembali {{ $rental->return_date->format('d M') }}</div>
                    </div>
                    @if ($rental->return_date->isPast())
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-700 text-xs font-medium rounded-full">Terlambat</span>
                    @endif
                    <a href="{{ route('staff.returns.create', ['rental_code' => $rental->rental_code]) }}" class="text-xs font-semibold text-sabana-700 hover:underline">Proses →</a>
                </div>
            @empty
                <p class="text-sm text-slate-500 text-center py-6">Tidak ada sewa aktif</p>
            @endforelse
        </div>
    </div>

    <div class="bg-sabana-700 text-white rounded-xl p-6">
        <x-icon name="arrow-uturn-left" class="w-7 h-7 mb-2 text-white/80"/>
        <div class="text-lg font-semibold mb-2">Cek Pengembalian</div>
        <p class="text-sm text-sabana-100 mb-4">Cari transaksi berdasarkan kode untuk memproses pengembalian.</p>
        <form method="GET" action="{{ route('staff.returns.create') }}" class="space-y-2">
            <input type="text" name="rental_code" placeholder="RNT-2026-001" required class="w-full px-4 py-2 rounded-lg text-sm text-slate-900">
            <button type="submit" class="w-full px-4 py-2 bg-white text-sabana-800 font-semibold rounded-lg text-sm hover:bg-sabana-50">Cari Transaksi</button>
        </form>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="p-5 border-b border-slate-200">
        <div class="text-base font-semibold text-slate-900">Riwayat Pengembalian</div>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <th class="py-3 px-4">Kode Return</th>
                <th class="py-3 px-4">Sewa</th>
                <th class="py-3 px-4">Customer</th>
                <th class="py-3 px-4">Tgl Kembali</th>
                <th class="py-3 px-4 text-center">Telat</th>
                <th class="py-3 px-4 text-right">Denda</th>
                <th class="py-3 px-4">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($returns as $return)
                <tr>
                    <td class="py-3 px-4 font-mono text-xs">{{ $return->return_code }}</td>
                    <td class="py-3 px-4 font-mono text-xs">
                        <a href="{{ route('staff.rentals.show', $return->rental) }}" class="text-sabana-700 hover:underline">{{ $return->rental->rental_code }}</a>
                    </td>
                    <td class="py-3 px-4">{{ $return->rental->customer->name }}</td>
                    <td class="py-3 px-4">{{ $return->actual_return_date->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        @if ($return->late_days > 0)
                            <span class="text-rose-600 font-semibold">{{ $return->late_days }} hari</span>
                        @else
                            <span class="text-emerald-600">Tepat waktu</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($return->total_fine, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        @if ($return->payment_status === 'paid')
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full">Lunas</span>
                        @elseif ($return->payment_status === 'unpaid')
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-full">Belum Lunas</span>
                        @else
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full">Dibebaskan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-12 text-slate-500">Belum ada catatan pengembalian</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-slate-200">{{ $returns->links() }}</div>
</div>
@endsection
