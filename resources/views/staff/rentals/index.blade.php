@extends('layouts.dashboard')
@section('title', 'Peminjaman')
@section('page-title', 'Transaksi Peminjaman')

@section('content')
<div class="bg-white border border-slate-200 rounded-xl p-5 mb-4">
    <form method="GET" class="grid md:grid-cols-5 gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari kode / customer..."
               class="md:col-span-2 px-4 py-2 border border-slate-300 rounded-lg text-sm">
        <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') === 'active')>Aktif</option>
            <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
            <option value="late" @selected(request('status') === 'late')>Terlambat</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>Dibatalkan</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium">Filter</button>
        <a href="{{ route('staff.rentals.create') }}" class="px-4 py-2 bg-sabana-700 text-white rounded-lg text-sm font-medium text-center">+ Sewa Baru</a>
    </form>
</div>

<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Customer</th>
                    <th class="py-3 px-4">Item</th>
                    <th class="py-3 px-4">Periode</th>
                    <th class="py-3 px-4">Staff</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Total</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($rentals as $rental)
                    <tr>
                        <td class="py-3 px-4">
                            <a href="{{ route('staff.rentals.show', $rental) }}" class="font-mono text-xs text-sabana-700 hover:underline">{{ $rental->rental_code }}</a>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium">{{ $rental->customer->name }}</div>
                            <div class="text-xs text-slate-500">{{ $rental->customer->phone }}</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 max-w-xs truncate">
                            {{ $rental->details->pluck('item.name')->take(2)->join(', ') }}
                            @if ($rental->details->count() > 2)
                                <span class="text-xs text-slate-400">+{{ $rental->details->count() - 2 }} lainnya</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-xs">
                            {{ $rental->rental_date->format('d M') }} - {{ $rental->return_date->format('d M Y') }}
                            <div class="text-slate-500">{{ $rental->duration_days }} hari</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">{{ $rental->staff?->name ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $rental->statusBadgeClass() }}">
                                {{ $rental->statusLabel() }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right">
                            @if (in_array($rental->rental_status, ['active', 'late']))
                                <a href="{{ route('staff.returns.create', ['rental_code' => $rental->rental_code]) }}" class="text-emerald-700 hover:text-emerald-800 text-xs font-medium">Return</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-12 text-slate-500">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-200">{{ $rentals->links() }}</div>
</div>
@endsection
