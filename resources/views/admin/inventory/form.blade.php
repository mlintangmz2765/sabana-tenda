@extends('layouts.dashboard')
@section('title', $item->exists ? 'Edit Barang' : 'Tambah Barang')
@section('page-title', $item->exists ? 'Edit Barang' : 'Tambah Barang Baru')

@section('content')
<form method="POST"
      action="{{ $item->exists ? route('admin.inventory.update', $item) : route('admin.inventory.store') }}"
      enctype="multipart/form-data"
      class="max-w-3xl"
      x-data="{ previewUrl: '{{ $item->image_path ? asset('storage/app/public/' . $item->image_path) : '' }}' }">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Nama Barang <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required maxlength="120"
                   class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-forest-500">
            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                <select name="category_id" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->name }}</option>
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

        {{-- IMAGE UPLOAD --}}
        <div>
            <label class="text-sm font-medium text-slate-700">Foto Produk</label>
            <div class="mt-2 flex items-start gap-4">
                {{-- Preview --}}
                <div class="w-32 h-32 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center overflow-hidden bg-slate-50 flex-shrink-0">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" class="w-full h-full object-cover" alt="Preview">
                    </template>
                    <template x-if="!previewUrl">
                        <div class="text-center text-slate-400">
                            <x-icon name="camera" class="w-8 h-8 mx-auto mb-1"/>
                            <span class="text-[10px]">No image</span>
                        </div>
                    </template>
                </div>
                {{-- Upload input --}}
                <div class="flex-1">
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                           @change="
                               const file = $event.target.files[0];
                               if (file) {
                                   previewUrl = URL.createObjectURL(file);
                               }
                           "
                           class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-forest-50 file:text-forest-700 file:rounded-lg hover:file:bg-forest-100 file:cursor-pointer">
                    <p class="text-xs text-slate-500 mt-2">Format: JPG, PNG, atau WebP. Maksimal 2MB.</p>
                    @error('image') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    @if ($item->image_path)
                        <label class="flex items-center gap-2 mt-2 text-xs text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded text-rose-600"
                                   @change="if ($event.target.checked) previewUrl = ''">
                            Hapus foto saat ini
                        </label>
                    @endif
                </div>
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
        <button type="submit" class="px-6 py-2 bg-forest-950 hover:bg-forest-800 text-white text-sm font-semibold rounded-lg shadow">
            {{ $item->exists ? 'Simpan Perubahan' : 'Tambah Barang' }}
        </button>
    </div>
</form>
@endsection
