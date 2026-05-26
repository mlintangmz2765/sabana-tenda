@extends('layouts.dashboard')
@section('title', 'Detail Sewa ' . $rental->rental_code)
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="max-w-5xl space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <div class="text-sm text-slate-500">Kode Transaksi</div>
                <div class="text-2xl font-mono font-bold text-sabana-700">{{ $rental->rental_code }}</div>
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $rental->statusBadgeClass() }}">
                        {{ $rental->statusLabel() }}
                    </span>
                    <span class="text-xs text-slate-500">Dibuat {{ $rental->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('staff.rentals.invoice', $rental) }}" target="_blank" class="px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-800 inline-flex items-center gap-1.5"><x-icon name="printer" class="w-4 h-4"/> Cetak Invoice</a>
                @if (in_array($rental->rental_status, ['active','late']))
                    <a href="{{ route('staff.returns.create', ['rental_code' => $rental->rental_code]) }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 inline-flex items-center gap-1.5"><x-icon name="arrow-uturn-left" class="w-4 h-4"/> Proses Return</a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="text-base font-semibold text-slate-900 mb-3">Pelanggan</div>
            <div class="space-y-2 text-sm">
                <div class="flex"><span class="w-24 text-slate-500">Kode</span><span class="font-mono">{{ $rental->customer->customer_code }}</span></div>
                <div class="flex"><span class="w-24 text-slate-500">Nama</span><span class="font-medium">{{ $rental->customer->name }}</span></div>
                <div class="flex"><span class="w-24 text-slate-500">Telepon</span><span>{{ $rental->customer->phone }}</span></div>
                <div class="flex"><span class="w-24 text-slate-500">{{ $rental->customer->id_card_type }}</span><span>{{ $rental->customer->id_card_number }}</span></div>
                <div class="flex"><span class="w-24 text-slate-500">Alamat</span><span class="flex-1">{{ $rental->customer->address }}</span></div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="text-base font-semibold text-slate-900 mb-3">Periode Sewa</div>
            <div class="space-y-2 text-sm">
                <div class="flex"><span class="w-32 text-slate-500">Tanggal Sewa</span><span class="font-medium">{{ $rental->rental_date->format('d M Y') }}</span></div>
                <div class="flex"><span class="w-32 text-slate-500">Tanggal Kembali</span><span>{{ $rental->return_date->format('d M Y') }}</span></div>
                <div class="flex"><span class="w-32 text-slate-500">Durasi</span><span>{{ $rental->duration_days }} hari</span></div>
                <div class="flex"><span class="w-32 text-slate-500">Staff</span><span>{{ $rental->staff?->name ?? '—' }}</span></div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <div class="text-base font-semibold text-slate-900">Detail Barang</div>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Barang</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4 text-center">Qty</th>
                    <th class="py-3 px-4 text-right">Harga/Hari</th>
                    <th class="py-3 px-4 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($rental->details as $detail)
                    <tr>
                        <td class="py-3 px-4 font-mono text-xs">{{ $detail->item->item_code }}</td>
                        <td class="py-3 px-4 font-medium">{{ $detail->item->name }}</td>
                        <td class="py-3 px-4 text-slate-600 text-xs">{{ $detail->item->category->name }}</td>
                        <td class="py-3 px-4 text-center">{{ $detail->quantity }}</td>
                        <td class="py-3 px-4 text-right">Rp {{ number_format($detail->price_per_day, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-sabana-50">
                <tr>
                    <td colspan="5" class="py-3 px-4 text-right font-semibold">TOTAL</td>
                    <td class="py-3 px-4 text-right text-lg font-bold text-sabana-700">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($rental->returnTransaction)
        @php $return = $rental->returnTransaction; @endphp
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="text-base font-semibold text-slate-900 mb-4">Catatan Pengembalian</div>
            <div class="grid md:grid-cols-4 gap-4 text-sm">
                <div>
                    <div class="text-xs text-slate-500">Kode Return</div>
                    <div class="font-mono font-semibold">{{ $return->return_code }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Tanggal Kembali</div>
                    <div class="font-medium">{{ $return->actual_return_date->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Keterlambatan</div>
                    <div class="font-medium {{ $return->late_days > 0 ? 'text-rose-600' : '' }}">{{ $return->late_days }} hari</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Total Denda</div>
                    <div class="font-bold text-rose-600">Rp {{ number_format($return->total_fine, 0, ',', '.') }}</div>
                </div>
            </div>
            @if ($return->condition_check)
                <div class="mt-4 p-3 bg-slate-50 rounded-lg text-sm">
                    <div class="text-xs text-slate-500 mb-1">Catatan Kondisi:</div>
                    {{ $return->condition_check }}
                </div>
            @endif
            @if ($return->damagedItems->isNotEmpty())
                <div class="mt-4">
                    <div class="text-sm font-semibold mb-2">Barang Rusak/Hilang</div>
                    <div class="space-y-2">
                        @foreach ($return->damagedItems as $damage)
                            <div class="flex items-center justify-between p-3 bg-rose-50 border border-rose-200 rounded-lg text-sm">
                                <div>
                                    <span class="font-medium">{{ $damage->item->name }}</span>
                                    <span class="text-xs ml-2 px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">{{ $damage->levelLabel() }}</span>
                                    <div class="text-xs text-slate-600 mt-1">{{ $damage->description }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-rose-700">Rp {{ number_format($damage->repair_cost, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
