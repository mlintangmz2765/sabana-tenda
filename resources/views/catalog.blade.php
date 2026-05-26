@extends('layouts.app')
@section('title', 'Katalog')

@section('content')
<section class="bg-forest-950 text-bone-50 grain relative">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-16 lg:py-20">
        <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-400 mb-4 flex items-center gap-3">
            <span class="w-10 h-px bg-ember-400"></span>
            Katalog &middot; {{ $items->total() }} produk
        </div>
        <h1 class="font-display text-5xl lg:text-7xl font-medium tracking-super-tight leading-[0.9]">
            Pilih alatmu,<br>
            <em class="italic font-light text-bone-300" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">mulai langkahmu.</em>
        </h1>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-10">
    <form method="GET" class="bg-bone-50 border-b border-bone-200 pb-6 mb-10 grid md:grid-cols-12 gap-4">
        <div class="md:col-span-6 relative">
            <x-icon name="search" class="w-4 h-4 absolute left-0 top-1/2 -translate-y-1/2 text-bone-500"/>
            <input name="search" value="{{ request('search') }}" placeholder="Cari nama barang, kode, atau spesifikasi..."
                   class="w-full pl-6 pr-2 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none text-sm">
        </div>
        <select name="category" class="md:col-span-3 px-2 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none text-sm appearance-none">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="md:col-span-3 px-5 py-3 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium inline-flex items-center justify-center gap-2 transition">
            Filter <x-icon name="arrow-right" class="w-4 h-4"/>
        </button>
    </form>

    @if ($items->isEmpty())
        <div class="text-center py-24">
            <x-icon name="search" class="w-10 h-10 text-bone-400 mx-auto mb-4"/>
            <p class="font-display text-2xl text-bone-700">Tidak ada hasil.</p>
            <p class="text-sm text-bone-500 mt-2">Coba kata kunci lain atau reset filter.</p>
        </div>
    @else
        @php
            $itemIcons = [
                'Tenda' => 'tent', 'Carrier & Tas' => 'backpack',
                'Sleeping Gear' => 'sleeping-bag', 'Alat Masak' => 'flame',
                'Lighting' => 'lantern', 'Perlengkapan Lain' => 'compass',
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-px bg-bone-200 border border-bone-200">
            @foreach ($items as $idx => $item)
                <a href="{{ route('catalog.show', $item) }}" class="group bg-bone-50 hover:bg-bone-100 transition relative">
                    <div class="aspect-[4/5] bg-gradient-to-br from-bone-100 via-bone-50 to-forest-50 relative overflow-hidden border-b border-bone-200">
                        @if ($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <x-icon :name="$itemIcons[$item->category->name] ?? 'mountain'" class="w-24 h-24 text-forest-700/30 group-hover:scale-110 transition-transform duration-500"/>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 font-mono text-[10px] tracking-[0.2em] text-bone-700">{{ $item->item_code }}</div>
                        @if($item->available_stock > 0)
                            <div class="absolute bottom-4 right-4 px-2 py-1 bg-forest-950 text-bone-50 font-mono text-[9px] tracking-wider uppercase">Stok {{ $item->available_stock }}</div>
                        @else
                            <div class="absolute bottom-4 right-4 px-2 py-1 bg-ember-600 text-bone-50 font-mono text-[9px] tracking-wider uppercase">Habis</div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 mb-2">{{ $item->category->name }}</div>
                        <div class="font-display text-xl font-medium text-forest-950 tracking-super-tight leading-tight">{{ $item->name }}</div>
                        <div class="mt-4 flex items-baseline justify-between">
                            <div>
                                <span class="font-display text-2xl font-medium text-forest-950">Rp{{ number_format($item->price_per_day / 1000, 0) }}k</span>
                                <span class="text-xs text-bone-700">/hari</span>
                            </div>
                            <x-icon name="arrow-up-right" class="w-4 h-4 text-forest-950 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 flex justify-center">{{ $items->links() }}</div>
    @endif
</section>
@endsection
