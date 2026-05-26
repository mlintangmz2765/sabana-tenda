@extends('layouts.dashboard')
@section('title', 'Proses Pengembalian')
@section('page-title', 'Proses Pengembalian Barang')

@section('content')
<div class="max-w-5xl">
    @if (! $rental)
        <div class="bg-white border border-slate-200 rounded-xl p-8 text-center">
            <x-icon name="search" class="w-12 h-12 mb-4 text-bone-400"/>
            <h2 class="text-xl font-semibold mb-2">Cari Transaksi</h2>
            <p class="text-slate-600 mb-6">Masukkan kode transaksi peminjaman yang akan dikembalikan.</p>
            <form method="GET" class="max-w-md mx-auto flex gap-2">
                <input type="text" name="rental_code" value="{{ request('rental_code') }}" placeholder="RNT-2026-001"
                       class="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-sabana-500">
                <button type="submit" class="px-6 py-2.5 bg-sabana-700 text-white text-sm font-semibold rounded-lg hover:bg-sabana-800">Cari</button>
            </form>
            @if (request('rental_code'))
                <p class="text-sm text-rose-600 mt-4">Transaksi tidak ditemukan atau sudah dikembalikan.</p>
            @endif
        </div>
    @else
        @php
            $dueDate = $rental->return_date->copy()->startOfDay();
            $today = now()->startOfDay();
            $lateDaysPreview = max(0, (int) $dueDate->diffInDays($today, false));
            $itemCount = $rental->details->sum('quantity');
            $lateFeePreview = $lateDaysPreview * (int) config('sabana.daily_penalty') * $itemCount;
        @endphp

        <form method="POST" action="{{ route('staff.returns.store', $rental) }}"
              x-data="returnForm({
                  rentalDate: '{{ $rental->rental_date->format('Y-m-d') }}',
                  dueDate: '{{ $rental->return_date->format('Y-m-d') }}',
                  dailyPenalty: {{ config('sabana.daily_penalty') }},
                  totalItems: {{ $itemCount }},
              })" class="space-y-6">
            @csrf

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-600 text-white flex items-center justify-center"><x-icon name="alert-triangle" class="w-6 h-6"/></div>
                    <div class="flex-1">
                        <div class="font-semibold text-amber-900">{{ $rental->rental_code }}</div>
                        <div class="text-sm text-amber-800">{{ $rental->customer->name }} · {{ $rental->customer->phone }}</div>
                        <div class="text-xs text-amber-700 mt-1">
                            Periode: {{ $rental->rental_date->format('d M') }} - {{ $rental->return_date->format('d M Y') }} ({{ $rental->duration_days }} hari)
                            @if ($lateDaysPreview > 0)
                                <span class="ml-2 font-bold text-rose-700">Terlambat {{ $lateDaysPreview }} hari!</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="text-base font-semibold text-slate-900 mb-4">Detail Pengembalian</div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Tanggal Pengembalian Aktual <span class="text-rose-500">*</span></label>
                        <input type="date" name="actual_return_date" x-model="actualReturnDate" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Status Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="payment_status" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                            <option value="paid">Lunas (Cash)</option>
                            <option value="unpaid">Belum Lunas</option>
                            <option value="waived">Dibebaskan</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium text-slate-700">Catatan Kondisi</label>
                    <textarea name="condition_check" rows="2" placeholder="Lengkap, kondisi baik..." class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">{{ old('condition_check') }}</textarea>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="text-base font-semibold text-slate-900 mb-4">Cek Kondisi Barang</div>
                <div class="space-y-3">
                    @foreach ($rental->details as $i => $detail)
                        <div class="border border-slate-200 rounded-lg p-4" x-data="{ damaged: false }">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-bone-100 flex items-center justify-center"><x-icon name="tent" class="w-5 h-5 text-forest-700"/></div>
                                    <div>
                                        <div class="font-medium">{{ $detail->item->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $detail->quantity }} unit · {{ $detail->item->item_code }}</div>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 text-sm font-medium">
                                    <input type="checkbox" x-model="damaged" class="rounded text-amber-600">
                                    Ada kerusakan / hilang
                                </label>
                            </div>

                            <div x-show="damaged" x-cloak class="mt-4 pt-4 border-t border-slate-200 grid md:grid-cols-3 gap-3">
                                <input type="hidden" :name="`damages[{{ $i }}][item_id]`" :value="damaged ? {{ $detail->item_id }} : ''">
                                <div>
                                    <label class="text-xs font-medium text-slate-700">Tingkat</label>
                                    <select :name="`damages[{{ $i }}][damage_level]`" class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded text-sm">
                                        <option value="minor">Rusak Ringan</option>
                                        <option value="heavy">Rusak Berat</option>
                                        <option value="lost">Hilang</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-medium text-slate-700">Deskripsi</label>
                                    <input type="text" :name="`damages[{{ $i }}][description]`" placeholder="Resleting tenda rusak..." class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded text-sm">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="text-xs font-medium text-slate-700">Biaya Perbaikan / Ganti Rugi (Rp)</label>
                                    <input type="number" :name="`damages[{{ $i }}][repair_cost]`" min="0" placeholder="50000" @input="$dispatch('damage-changed')" class="w-full mt-1 px-3 py-1.5 border border-slate-300 rounded text-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-sabana-50 border border-sabana-200 rounded-xl p-6">
                <div class="text-base font-semibold text-sabana-900 mb-4">Perhitungan Denda Otomatis</div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Hari Keterlambatan</span>
                            <span class="font-semibold" x-text="lateDays + ' hari'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Denda/Hari × Item</span>
                            <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(dailyPenalty * totalItems)"></span>
                        </div>
                        <div class="flex justify-between font-medium">
                            <span>Subtotal Denda Telat</span>
                            <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(lateFee)"></span>
                        </div>
                        <div class="flex justify-between font-medium">
                            <span>Subtotal Kerusakan</span>
                            <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(damageFee)"></span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-5 flex flex-col justify-center items-center">
                        <div class="text-xs text-slate-500 uppercase font-semibold">Total Denda</div>
                        <div class="text-3xl font-bold text-rose-600" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalFine)"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('staff.returns.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow">
                    <x-icon name="check" class="w-4 h-4 mr-1"/> Konfirmasi Pengembalian
                </button>
            </div>
        </form>
    @endif
</div>

@push('scripts')
<script>
function returnForm(opts) {
    return {
        actualReturnDate: '{{ now()->format('Y-m-d') }}',
        dailyPenalty: opts.dailyPenalty,
        totalItems: opts.totalItems,
        damageFee: 0,
        get lateDays() {
            const due = new Date(opts.dueDate);
            const actual = new Date(this.actualReturnDate);
            const diff = Math.floor((actual - due) / (1000*60*60*24));
            return Math.max(0, diff);
        },
        get lateFee() { return this.lateDays * this.dailyPenalty * this.totalItems; },
        get totalFine() { return this.lateFee + this.damageFee; },
        init() {
            window.addEventListener('damage-changed', () => this.recomputeDamage());
        },
        recomputeDamage() {
            let total = 0;
            document.querySelectorAll('input[name^="damages"][name$="[repair_cost]"]').forEach(el => {
                total += parseInt(el.value) || 0;
            });
            this.damageFee = total;
        },
    };
}
</script>
@endpush
@endsection
