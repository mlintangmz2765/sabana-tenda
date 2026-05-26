@extends('layouts.app')
@section('title', $item->name)

@section('content')
@php
    $itemIcons = [
        'Tenda' => 'tent', 'Carrier & Tas' => 'backpack',
        'Sleeping Gear' => 'sleeping-bag', 'Alat Masak' => 'flame',
        'Lighting' => 'lantern', 'Perlengkapan Lain' => 'compass',
    ];
    $iconName = $itemIcons[$item->category->name] ?? 'mountain';
@endphp
<div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-10">
    <nav class="font-mono text-[11px] tracking-[0.15em] uppercase text-bone-700 mb-10 flex items-center gap-3">
        <a href="{{ route('home') }}" class="hover:text-forest-950">Beranda</a>
        <x-icon name="chevron-right" class="w-3 h-3"/>
        <a href="{{ route('catalog') }}" class="hover:text-forest-950">Katalog</a>
        <x-icon name="chevron-right" class="w-3 h-3"/>
        <a href="{{ route('catalog', ['category' => $item->category->slug]) }}" class="hover:text-forest-950">{{ $item->category->name }}</a>
        <x-icon name="chevron-right" class="w-3 h-3"/>
        <span class="text-forest-950">{{ $item->item_code }}</span>
    </nav>

    <div class="grid lg:grid-cols-12 gap-12 lg:gap-16">
        <div class="lg:col-span-7">
            <div class="aspect-square bg-gradient-to-br from-bone-100 via-bone-50 to-forest-50 border border-bone-200 relative overflow-hidden">
                <x-icon :name="$iconName" class="absolute inset-0 m-auto w-1/2 h-1/2 text-forest-700/30"/>
                <div class="absolute top-6 left-6 font-mono text-[10px] tracking-[0.25em] uppercase text-bone-700">{{ $item->item_code }}</div>
                <div class="absolute bottom-6 left-6 font-mono text-[10px] tracking-[0.25em] uppercase text-bone-700">{{ $item->category->name }}</div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-4">{{ $item->category->name }}</div>
            <h1 class="font-display text-5xl font-medium tracking-super-tight text-forest-950 leading-[0.95]">{{ $item->name }}</h1>

            <div class="mt-8 pb-8 border-b border-bone-200">
                <span class="font-display text-5xl font-medium text-forest-950">Rp{{ number_format($item->price_per_day, 0, ',', '.') }}</span>
                <span class="text-bone-700 ml-2">/ hari</span>
            </div>

            <div class="mt-8 space-y-3 text-sm">
                <div class="flex items-center justify-between border-b border-bone-200 pb-3">
                    <span class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Ketersediaan</span>
                    @if($item->available_stock > 0)
                        <span class="font-medium text-forest-700 flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4"/>
                            {{ $item->available_stock }} unit siap sewa
                        </span>
                    @else
                        <span class="font-medium text-ember-600 flex items-center gap-2">
                            <x-icon name="x-circle" class="w-4 h-4"/>
                            Habis
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between border-b border-bone-200 pb-3">
                    <span class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Kondisi</span>
                    <span class="font-medium text-forest-950">{{ $item->conditionLabel() }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-bone-200 pb-3">
                    <span class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Maks. Sewa</span>
                    <span class="font-medium text-forest-950">{{ config('sabana.max_rental_days') }} hari</span>
                </div>
            </div>

            @if ($item->description)
                <div class="mt-8">
                    <div class="font-mono text-[10px] tracking-[0.25em] uppercase text-bone-700 mb-3">Deskripsi</div>
                    <p class="text-sm text-forest-950 leading-relaxed">{{ $item->description }}</p>
                </div>
            @endif

            @if ($item->specifications)
                <div class="mt-8">
                    <div class="font-mono text-[10px] tracking-[0.25em] uppercase text-bone-700 mb-3">Spesifikasi</div>
                    <pre class="text-sm text-forest-950 leading-relaxed whitespace-pre-wrap font-sans bg-bone-100 p-4 border-l-2 border-forest-700">{{ $item->specifications }}</pre>
                </div>
            @endif

            <div class="mt-10 flex flex-wrap gap-3">
                @auth
                    @if (auth()->user()->isCustomer())
                        <a href="{{ route('catalog') }}"
                           class="inline-flex items-center gap-3 px-7 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium transition group">
                            <x-icon name="shopping-bag" class="w-4 h-4"/>
                            Sewa Sekarang
                            <x-icon name="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
                        </a>
                    @else
                        <a href="{{ route('staff.rentals.create') }}?item_id={{ $item->id }}" class="inline-flex items-center gap-3 px-7 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium transition">
                            <x-icon name="clipboard-list" class="w-4 h-4"/> Buat Transaksi
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-3 px-7 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium transition">
                        <x-icon name="log-in" class="w-4 h-4"/> Masuk untuk Sewa
                    </a>
                @endauth
                <a href="{{ route('catalog', ['category' => $item->category->slug]) }}" class="inline-flex items-center gap-3 px-7 py-4 border border-forest-950 hover:bg-forest-950 hover:text-bone-50 text-sm font-medium transition">
                    <x-icon name="search" class="w-4 h-4"/> Lihat Kategori Lain
                </a>
            </div>

            <div class="mt-10 bg-bone-100 border-l-2 border-ember-500 p-5 text-xs text-bone-700 space-y-1.5">
                <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember-600 mb-2">Ketentuan</div>
                <div>&middot; Denda keterlambatan: Rp {{ number_format(config('sabana.daily_penalty'), 0, ',', '.') }}/hari per item</div>
                <div>&middot; Wajib menunjukkan KTP/SIM sebagai jaminan</div>
                <div>&middot; Pembayaran tunai saat pengambilan</div>
            </div>
        </div>
    </div>

    @if ($relatedItems->isNotEmpty())
        <div class="mt-24 pt-12 border-t border-bone-200">
            <div class="flex items-end justify-between mb-10 flex-wrap gap-6">
                <div>
                    <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-3">Serupa</div>
                    <h2 class="font-display text-3xl font-medium text-forest-950 tracking-super-tight">Mungkin kamu juga butuh ini.</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-bone-200 border border-bone-200">
                @foreach ($relatedItems as $related)
                    <a href="{{ route('catalog.show', $related) }}" class="group bg-bone-50 hover:bg-bone-100 transition">
                        <div class="aspect-square bg-gradient-to-br from-bone-100 via-bone-50 to-forest-50 flex items-center justify-center">
                            <x-icon :name="$itemIcons[$related->category->name] ?? 'mountain'" class="w-16 h-16 text-forest-700/30 group-hover:scale-110 transition-transform duration-500"/>
                        </div>
                        <div class="p-4">
                            <div class="font-display text-lg font-medium text-forest-950 tracking-super-tight">{{ $related->name }}</div>
                            <div class="text-sm font-medium mt-1">Rp{{ number_format($related->price_per_day / 1000, 0) }}k<span class="text-xs text-bone-700">/hari</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
