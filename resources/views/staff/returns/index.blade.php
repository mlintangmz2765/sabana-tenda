@extends('layouts.dashboard')
@section('title', 'Pengembalian')
@section('page-title', 'Pengembalian Barang')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 card-stack p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="text-base font-semibold text-forest-950">Sewa Aktif &amp; Terlambat</div>
            <a href="{{ route('staff.returns.create') }}" class="px-3 py-1.5 bg-forest-950 text-bone-50 text-xs font-semibold">+ Proses Return</a>
        </div>
        <div class="space-y-2 max-h-96 overflow-y-auto scrollbar-thin">
            @forelse ($pendingReturns as $rental)
                <div class="flex items-center gap-3 p-3 border border-bone-200 hover:bg-bone-50 transition">
                    <div class="w-10 h-10 border border-forest-950 flex items-center justify-center font-display text-forest-950">
                        {{ strtoupper(substr($rental->customer->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-forest-950">{{ $rental->customer->name }}</div>
                        <div class="font-mono text-[10px] tracking-wider uppercase text-bone-700">{{ $rental->rental_code }} · {{ $rental->details->count() }} item · Kembali {{ $rental->return_date->format('d M') }}</div>
                    </div>
                    @if ($rental->return_date->isPast())
                        <span class="px-2 py-0.5 bg-ember-300/40 text-ember-700 font-mono text-[9px] tracking-wider uppercase">Terlambat</span>
                    @endif
                    <a href="{{ route('staff.returns.create', ['rental_code' => $rental->rental_code]) }}" class="text-forest-950 hover:text-forest-700"><x-icon name="arrow-up-right" class="w-4 h-4"/></a>
                </div>
            @empty
                <p class="text-sm text-bone-500 text-center py-6">Tidak ada sewa aktif</p>
            @endforelse
        </div>
    </div>

    <div class="bg-forest-950 text-bone-50 p-6">
        <x-icon name="arrow-uturn-left" class="w-7 h-7 mb-3 text-ember-400"/>
        <div class="font-display text-xl font-medium tracking-super-tight mb-2">Cek Pengembalian</div>
        <p class="text-sm text-bone-300 mb-4">Cari transaksi berdasarkan kode untuk memproses pengembalian.</p>
        <form method="GET" action="{{ route('staff.returns.create') }}" class="space-y-2">
            <input type="text" name="rental_code" placeholder="RNT-2026-001" required class="w-full px-4 py-2 bg-bone-50 border border-bone-200 text-forest-950 text-sm font-mono">
            <button type="submit" class="w-full px-4 py-2 bg-ember-500 text-white font-semibold text-sm hover:bg-ember-600 transition">Cari Transaksi</button>
        </form>
    </div>
</div>

<div class="card-stack overflow-hidden">
    <div class="p-6 border-b border-bone-200">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-1">Riwayat</div>
        <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight">Pengembalian</div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 border-b border-bone-200">
                    <th class="py-3 px-6">Kode Return</th>
                    <th class="py-3 px-6">Sewa</th>
                    <th class="py-3 px-6">Customer</th>
                    <th class="py-3 px-6">Tgl Kembali</th>
                    <th class="py-3 px-6 text-center">Telat</th>
                    <th class="py-3 px-6 text-right">Denda</th>
                    <th class="py-3 px-6">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-bone-200">
                @forelse ($returns as $return)
                    <tr class="hover:bg-bone-50">
                        <td class="py-4 px-6 font-mono text-xs text-bone-700">{{ $return->return_code }}</td>
                        <td class="py-4 px-6 font-mono text-xs">
                            <a href="{{ route('staff.rentals.show', $return->rental) }}" class="text-forest-700 hover:underline">{{ $return->rental->rental_code }}</a>
                        </td>
                        <td class="py-4 px-6 font-medium text-forest-950">{{ $return->rental->customer->name }}</td>
                        <td class="py-4 px-6 font-mono text-xs text-bone-700">{{ $return->actual_return_date->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-center">
                            @if ($return->late_days > 0)
                                <span class="text-ember-600 font-semibold">{{ $return->late_days }} hari</span>
                            @else
                                <span class="text-forest-600">Tepat waktu</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right font-semibold ticker">Rp {{ number_format($return->total_fine, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            @if ($return->payment_status === 'paid')
                                <span class="px-2 py-0.5 bg-forest-50 text-forest-700 text-xs font-medium font-mono tracking-wider uppercase">Lunas</span>
                            @elseif ($return->payment_status === 'unpaid')
                                <span class="px-2 py-0.5 bg-ember-300/30 text-ember-700 text-xs font-medium font-mono tracking-wider uppercase">Belum Lunas</span>
                            @else
                                <span class="px-2 py-0.5 bg-bone-200 text-bone-700 text-xs font-medium font-mono tracking-wider uppercase">Dibebaskan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-12 text-bone-500">Belum ada catatan pengembalian</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-bone-200">{{ $returns->links() }}</div>
</div>
@endsection
