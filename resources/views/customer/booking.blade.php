@extends('layouts.dashboard')
@section('title', 'Booking Sewa')
@section('page-title', 'Booking Sewa Baru')

@section('content')
<form method="POST" action="{{ route('customer.booking.store') }}"
      x-data="bookingForm()"
      x-init="init()"
      class="max-w-5xl space-y-6">
    @csrf

    @if ($errors->any())
        <div class="bg-ember-300/30 border border-ember-600 p-5 text-sm text-ember-700 space-y-1">
            <div class="font-semibold flex items-center gap-2"><x-icon name="alert-triangle" class="w-4 h-4"/> Terjadi Kesalahan</div>
            @foreach ($errors->all() as $error)
                <div>&middot; {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card-stack p-6 lg:p-8">
        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Langkah 1</div>
        <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight mb-6">Pilih Periode Sewa</div>
        <div class="grid md:grid-cols-3 gap-6">
            <div>
                <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Tanggal Sewa</label>
                <input type="date" name="rental_date" x-model="rentalDate" required
                       class="w-full mt-2 px-4 py-3 bg-bone-50 border border-bone-200 text-forest-950 text-sm focus:border-forest-700 focus:outline-none">
            </div>
            <div>
                <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Tanggal Kembali</label>
                <input type="date" name="return_date" x-model="returnDate" required
                       class="w-full mt-2 px-4 py-3 bg-bone-50 border border-bone-200 text-forest-950 text-sm focus:border-forest-700 focus:outline-none">
            </div>
            <div>
                <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Durasi</label>
                <div class="w-full mt-2 px-4 py-3 bg-forest-950 text-bone-50 text-sm font-display text-xl font-medium tracking-super-tight"
                     x-text="duration + ' hari'"></div>
            </div>
        </div>
    </div>

    <div class="card-stack p-6 lg:p-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Langkah 2</div>
                <div class="font-display text-2xl font-medium text-forest-950 tracking-super-tight">Pilih Barang</div>
            </div>
            <button type="button" @click="addItem()"
                    class="px-4 py-2 bg-forest-950 text-bone-50 text-xs font-semibold hover:bg-forest-800 transition inline-flex items-center gap-2">
                <x-icon name="plus" class="w-3.5 h-3.5"/> Tambah Barang
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 border-b border-bone-200">
                        <th class="py-3 pr-4">Barang</th>
                        <th class="py-3 pr-4 text-center w-20">Stok</th>
                        <th class="py-3 pr-4 text-center w-20">Qty</th>
                        <th class="py-3 pr-4 text-right w-32">Harga/Hari</th>
                        <th class="py-3 pr-4 text-right w-32">Subtotal</th>
                        <th class="py-3 pr-4 w-12"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="border-b border-bone-200">
                            <td class="py-3 pr-4">
                                <select :name="`items[${index}][item_id]`" x-model="row.item_id" @change="onItemChange(index)" required
                                        class="w-full px-3 py-2 bg-bone-50 border border-bone-200 text-sm text-forest-950 focus:border-forest-700 focus:outline-none">
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
                            <td class="py-3 pr-4 text-center text-bone-700 font-mono text-xs" x-text="row.stock || '—'"></td>
                            <td class="py-3 pr-4">
                                <input type="number" :name="`items[${index}][quantity]`" x-model.number="row.quantity"
                                       @input="recalc()" :max="row.stock" min="1" required
                                       class="w-full px-3 py-2 bg-bone-50 border border-bone-200 text-sm text-center text-forest-950 focus:border-forest-700 focus:outline-none">
                            </td>
                            <td class="py-3 pr-4 text-right text-bone-700 font-mono text-xs" x-text="row.price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(row.price) : '—'"></td>
                            <td class="py-3 pr-4 text-right font-semibold text-forest-950 ticker" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(row.subtotal)"></td>
                            <td class="py-3 pr-4 text-center">
                                <button type="button" @click="removeItem(index)" class="text-ember-500 hover:text-ember-700">
                                    <x-icon name="x-circle" class="w-4 h-4"/>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="py-4 pr-4 text-right font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Total Biaya:</td>
                        <td class="py-4 pr-4 text-right font-display text-2xl font-medium text-forest-950 tracking-super-tight ticker" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalCost)"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6">
            <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Catatan (Opsional)</label>
            <textarea name="notes" rows="2" placeholder="Catatan khusus untuk booking ini..."
                      class="w-full mt-2 px-4 py-3 bg-bone-50 border border-bone-200 text-sm text-forest-950 focus:border-forest-700 focus:outline-none"></textarea>
        </div>
    </div>

    <div class="bg-forest-950 text-bone-50 p-6 lg:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-400">Total Pembayaran</div>
            <div class="font-display text-4xl font-medium tracking-super-tight mt-1 ticker" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalCost)"></div>
            <div class="font-mono text-xs text-bone-300 mt-1" x-text="duration + ' hari × ' + itemCount + ' item'"></div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('customer.dashboard') }}" class="px-5 py-3 text-sm font-medium text-bone-300 hover:text-bone-50 transition">Batal</a>
            <button type="submit" :disabled="!canSubmit"
                    class="px-8 py-3 bg-ember-500 hover:bg-ember-600 text-white text-sm font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed inline-flex items-center gap-2 group">
                Konfirmasi Booking
                <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"/>
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
function bookingForm() {
    return {
        rentalDate: '{{ now()->format("Y-m-d") }}',
        returnDate: '{{ now()->addDays(3)->format("Y-m-d") }}',
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
