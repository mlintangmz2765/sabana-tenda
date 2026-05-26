@extends('layouts.dashboard')
@section('title', $item->exists ? 'Edit Barang' : 'Tambah Barang')
@section('page-title', $item->exists ? 'Edit Barang' : 'Tambah Barang Baru')

@section('content')
<form method="POST"
      action="{{ $item->exists ? route('admin.inventory.update', $item) : route('admin.inventory.store') }}"
      class="max-w-3xl">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Nama Barang <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required maxlength="120"
                   class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-sabana-500">
            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                <select name="category_id" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Harga/Hari (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="price_per_day" value="{{ old('price_per_day', $item->price_per_day) }}" min="0" required
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Total Stok <span class="text-rose-500">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $item->stock) }}" min="0" required
                       class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                @if ($item->exists)
                    <p class="text-xs text-slate-500 mt-1">Saat ini tersedia: {{ $item->available_stock }} unit</p>
                @endif
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Kondisi <span class="text-rose-500">*</span></label>
                <select name="condition" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="good" @selected(old('condition', $item->condition) === 'good')>Baik</option>
                    <option value="minor_damage" @selected(old('condition', $item->condition) === 'minor_damage')>Rusak Ringan</option>
                    <option value="heavy_damage" @selected(old('condition', $item->condition) === 'heavy_damage')>Rusak Berat</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">{{ old('description', $item->description) }}</textarea>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-700">Spesifikasi</label>
            <textarea name="specifications" rows="4" placeholder="Kapasitas: 4 orang&#10;Dimensi: 210 x 210 x 130 cm&#10;Bahan: Polyester 210T"
                      class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm font-mono">{{ old('specifications', $item->specifications) }}</textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('admin.inventory.index') }}" class="px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Batal</a>
        <button type="submit" class="px-6 py-2 bg-sabana-700 hover:bg-sabana-800 text-white text-sm font-semibold rounded-lg shadow">
            {{ $item->exists ? 'Simpan Perubahan' : 'Tambah Barang' }}
        </button>
    </div>
</form>
@endsection
