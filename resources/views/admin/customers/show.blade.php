@extends('layouts.dashboard')
@section('title', $customer->name)
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-5xl space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-sabana-100 text-sabana-700 flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="text-2xl font-bold text-slate-900">{{ $customer->name }}</div>
                <div class="text-sm text-slate-500">{{ $customer->customer_code }} · {{ $customer->phone }} {{ $customer->email ? '· ' . $customer->email : '' }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $customer->address }}</div>
            </div>
            <div class="text-right">
                <div class="text-xs text-slate-500">Total Belanja</div>
                <div class="text-2xl font-bold text-sabana-700">Rp {{ number_format($customer->totalSpent(), 0, ',', '.') }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $customer->rentals->count() }} transaksi</div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <div class="text-base font-semibold text-slate-900">Riwayat Peminjaman</div>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Periode</th>
                    <th class="py-3 px-4">Item</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Total</th>
                    <th class="py-3 px-4 text-right">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($customer->rentals as $rental)
                    <tr>
                        <td class="py-3 px-4">
                            <a href="{{ route('staff.rentals.show', $rental) }}" class="font-mono text-xs text-sabana-700 hover:underline">{{ $rental->rental_code }}</a>
                        </td>
                        <td class="py-3 px-4 text-xs">{{ $rental->rental_date->format('d M') }} - {{ $rental->return_date->format('d M Y') }}</td>
                        <td class="py-3 px-4 max-w-xs truncate">{{ $rental->details->pluck('item.name')->join(', ') }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $rental->statusBadgeClass() }}">{{ $rental->statusLabel() }}</span>
                        </td>
                        <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right">
                            @if ($rental->returnTransaction)
                                <span class="text-rose-600 font-medium">Rp {{ number_format($rental->returnTransaction->total_fine, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-12 text-slate-500">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
