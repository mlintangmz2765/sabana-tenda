@extends('layouts.dashboard')
@section('title', 'Sewa Baru')
@section('page-title', 'Buat Transaksi Sewa Baru')

@section('content')
<form method="POST" action="{{ route('staff.rentals.store') }}"
      x-data="rentalForm()"
      x-init="init()"
      class="max-w-5xl space-y-6">
    @csrf

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <div class="text-base font-semibold text-slate-900 mb-4">Data Pelanggan</div>

        <div class="flex items-center gap-3 mb-4">
            <label class="flex items-center gap-2 text-sm">
                <input type="radio" name="customer_mode" value="existing" x-model="customerMode" class="text-sabana-600">
                Pelanggan Lama
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="radio" name="customer_mode" value="new" x-model="customerMode" class="text-sabana-600">
                Pelanggan Baru
            </label>
        </div>

        <div x-show="customerMode === 'existing'">
            <label class="text-sm font-medium text-slate-700">Pilih Pelanggan</label>
            <select name="customer_id" :required="customerMode === 'existing'" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                <option value="">Cari/pilih pelanggan...</option>
                @foreach ($customers as $cust)
                    <option value="{{ $cust->id }}">{{ $cust->customer_code }} · {{ $cust->name }} · {{ $cust->phone }}</option>
                @endforeach
            </select>
        </div>

        <div x-show="customerMode === 'new'" x-cloak class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Nama</label>
                <input type="text" name="new_customer_name" :required="customerMode === 'new'" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">No. Telepon</label>
                <input type="text" name="new_customer_phone" :required="customerMode === 'new'" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="new_customer_address" rows="2" :required="customerMode === 'new'" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700">No. KTP/Jaminan</label>
                <input type="text" name="new_customer_id_card" :required="customerMode === 'new'" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <div class="text-base font-semibold text-slate-900 mb-4">Periode Sewa</div>
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Tanggal Sewa</label>
                <input type="date" name="rental_date" x-model="rentalDate" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Tanggal Kembali</label>
                <input type="date" name="return_date" x-model="returnDate" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Durasi</label>
                <div class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm" x-text="duration + ' hari'"></div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-base font-semibold text-slate-900">Daftar Barang</div>
            <button type="button" @click="addItem()" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200 hover:bg-emerald-100">+ Tambah Barang</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-500 uppercase border-b border-slate-200">
                        <th class="py-2 pr-4">Barang</th>
                        <th class="py-2 pr-4 text-center w-24">Stok</th>
                        <th class="py-2 pr-4 text-center w-24">Qty</th>
                        <th class="py-2 pr-4 text-right w-32">Harga/Hari</th>
                        <th class="py-2 pr-4 text-right w-32">Subtotal</th>
                        <th class="py-2 pr-4 w-12"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="border-b border-slate-100">
                            <td class="py-2 pr-4">
                                <select :name="`items[${index}][item_id]`" x-model="row.item_id" @change="onItemChange(index)" required class="w-full px-3 py-1.5 border border-slate-300 rounded text-sm">
                                    <option value="">Pilih barang...</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}"
                                                data-price="{{ $item->price_per_day }}"
                                                data-stock="{{ $item->available_stock }}"
                                                data-name="{{ $item->name }}">
                                            {{ $item->item_code }} · {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2 pr-4 text-center text-slate-600" x-text="row.stock || '—'"></td>
                            <td class="py-2 pr-4">
                                <input type="number" :name="`items[${index}][quantity]`" x-model.number="row.quantity" @input="recalc()" :max="row.stock" min="1" required class="w-full px-3 py-1.5 border border-slate-300 rounded text-sm text-center">
                            </td>
                            <td class="py-2 pr-4 text-right text-slate-600" x-text="row.price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(row.price) : '—'"></td>
                            <td class="py-2 pr-4 text-right font-semibold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(row.subtotal)"></td>
                            <td class="py-2 pr-4 text-center">
                                <button type="button" @click="removeItem(index)" class="text-rose-500 hover:text-rose-700">×</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="py-3 pr-4 text-right text-sm text-slate-500">Total Biaya:</td>
                        <td class="py-3 pr-4 text-right text-lg font-bold text-sabana-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalCost)"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4">
            <label class="text-sm font-medium text-slate-700">Catatan (Opsional)</label>
            <textarea name="notes" rows="2" placeholder="Catatan khusus untuk transaksi ini..." class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
        </div>
    </div>

    <div class="flex items-center justify-between bg-sabana-50 border border-sabana-200 rounded-xl p-5">
        <div>
            <div class="text-xs text-sabana-700 font-semibold uppercase">Total Pembayaran</div>
            <div class="text-3xl font-bold text-sabana-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalCost)"></div>
            <div class="text-xs text-slate-600 mt-1" x-text="duration + ' hari × ' + itemCount + ' item'"></div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('staff.rentals.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Batal</a>
            <button type="submit" :disabled="!canSubmit" class="px-6 py-2.5 bg-sabana-700 hover:bg-sabana-800 text-white text-sm font-semibold rounded-lg shadow disabled:opacity-50">
                Simpan Transaksi →
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
function rentalForm() {
    return {
        customerMode: 'existing',
        rentalDate: '{{ now()->format('Y-m-d') }}',
        returnDate: '{{ now()->addDays(3)->format('Y-m-d') }}',
        rows: [{ item_id: '', quantity: 1, price: 0, stock: 0, subtotal: 0 }],
        get duration() {
            if (!this.rentalDate || !this.returnDate) return 0;
            const d = (new Date(this.returnDate) - new Date(this.rentalDate)) / (1000*60*60*24);
            return Math.max(0, Math.round(d));
        },
        get totalCost() { return this.rows.reduce((s, r) => s + (r.subtotal || 0), 0); },
        get itemCount() { return this.rows.reduce((s, r) => s + (parseInt(r.quantity) || 0), 0); },
        get canSubmit() { return this.duration > 0 && this.rows.some(r => r.item_id && r.quantity >= 1); },
        init() { this.$watch('rentalDate', () => this.recalc()); this.$watch('returnDate', () => this.recalc()); },
        addItem() { this.rows.push({ item_id: '', quantity: 1, price: 0, stock: 0, subtotal: 0 }); },
        removeItem(i) { if (this.rows.length > 1) this.rows.splice(i, 1); },
        onItemChange(i) {
            const sel = document.querySelector(`select[name="items[${i}][item_id]"]`);
            const opt = sel?.selectedOptions?.[0];
            if (opt && opt.value) {
                this.rows[i].price = parseInt(opt.dataset.price) || 0;
                this.rows[i].stock = parseInt(opt.dataset.stock) || 0;
                if (this.rows[i].quantity > this.rows[i].stock) this.rows[i].quantity = this.rows[i].stock;
            } else {
                this.rows[i].price = 0; this.rows[i].stock = 0;
            }
            this.recalc();
        },
        recalc() {
            this.rows.forEach(r => {
                r.subtotal = (r.price || 0) * (r.quantity || 0) * this.duration;
            });
        },
    };
}
</script>
@endpush
@endsection
