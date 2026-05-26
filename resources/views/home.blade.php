@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<!-- HERO: Editorial split with massive serif numerals -->
<section class="relative bg-bone-50 overflow-hidden grain">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-forest-950 hidden lg:block"></div>

    <div class="relative max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-16 lg:pt-24 pb-16 lg:pb-32">
        <div class="grid lg:grid-cols-12 gap-y-12 lg:gap-x-12 items-end">
            <!-- Left -->
            <div class="lg:col-span-7 relative z-10">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-6 reveal flex items-center gap-3">
                    <span class="w-10 h-px bg-ember-600"></span>
                    Rental Alat Camping &middot; Yogyakarta
                </div>

                <h1 class="font-display text-[14vw] sm:text-[10vw] lg:text-[7.5rem] xl:text-[9rem] font-medium leading-[0.85] tracking-super-tight text-forest-950 reveal">
                    Bawa<br>
                    <em class="italic font-light text-ember-600" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">petualangan</em><br>
                    ke gunung.
                </h1>

                <p class="text-base sm:text-lg text-bone-700 max-w-lg mt-8 reveal-2">
                    Sewa alat camping lengkap &mdash; tenda, sleeping bag, carrier, kompor, lampu &mdash; dengan sistem terotomatisasi.
                    Stok real-time, invoice digital, denda transparan.
                </p>

                <div class="flex flex-wrap gap-4 mt-10 reveal-3">
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-3 px-7 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium tracking-wide transition group">
                        Jelajahi Katalog
                        <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"/>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-7 py-4 border border-forest-950 text-forest-950 hover:bg-forest-950 hover:text-bone-50 text-sm font-medium tracking-wide transition">
                        <x-icon name="user" class="w-4 h-4"/>
                        Daftar & Sewa
                    </a>
                </div>
            </div>

            <!-- Right: stats card with mountains -->
            <div class="lg:col-span-5 lg:pl-8 relative">
                <div class="bg-forest-950 lg:bg-transparent text-bone-50 p-8 lg:p-0 relative">
                    <div class="font-mono text-[10px] tracking-[0.25em] uppercase text-ember-400 mb-8 reveal-2 flex items-center gap-3">
                        <span class="w-8 h-px bg-ember-400"></span>
                        Sejak 2020
                    </div>

                    <div class="space-y-8 reveal-3">
                        <div class="flex items-baseline justify-between border-b border-bone-50/20 pb-6">
                            <div>
                                <div class="font-display text-6xl font-medium leading-none tracking-super-tight ticker">128</div>
                                <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-300 mt-2">unit inventaris</div>
                            </div>
                            <x-icon name="backpack" class="w-8 h-8 text-ember-400"/>
                        </div>
                        <div class="flex items-baseline justify-between border-b border-bone-50/20 pb-6">
                            <div>
                                <div class="font-display text-6xl font-medium leading-none tracking-super-tight ticker">500<span class="text-ember-400">+</span></div>
                                <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-300 mt-2">pelanggan terlayani</div>
                            </div>
                            <x-icon name="users" class="w-8 h-8 text-ember-400"/>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <div>
                                <div class="font-display text-6xl font-medium leading-none tracking-super-tight ticker">4.9</div>
                                <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-300 mt-2">rating pelanggan</div>
                            </div>
                            <x-icon name="star" class="w-8 h-8 text-ember-400"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- decorative mountain silhouette at bottom of hero -->
    <svg class="hidden lg:block absolute bottom-0 left-1/2 -translate-x-1/2 w-[800px] text-bone-200 pointer-events-none" viewBox="0 0 800 200" fill="currentColor" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 200 L0 150 L100 80 L210 140 L320 50 L450 130 L580 70 L700 140 L800 90 L800 200 Z"/>
    </svg>
</section>

<!-- CATEGORIES: Magazine-style index -->
<section class="bg-bone-100 border-t border-bone-200">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-24">
        <div class="grid lg:grid-cols-12 gap-8 mb-16">
            <div class="lg:col-span-4">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-4 flex items-center gap-3">
                    <span class="font-display italic text-2xl text-ember-600" style="font-variation-settings: 'opsz' 60, 'SOFT' 100, 'wght' 400;">i.</span>
                    Kategori
                </div>
                <h2 class="font-display text-5xl font-medium text-forest-950 tracking-super-tight leading-[0.95]">
                    Setiap medan,<br>
                    <em class="italic font-light text-bone-700" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">alatnya sendiri.</em>
                </h2>
            </div>
            <div class="lg:col-span-7 lg:col-start-6 self-end">
                <p class="text-base text-bone-700 max-w-md">
                    Dari tenda dome ringkas untuk solo trip, sampai carrier 80L untuk ekspedisi panjang.
                    Setiap kategori dipilih supaya kamu fokus ke perjalanan, bukan ribet alatnya.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-bone-200 border border-bone-200">
            @php
                $catIcons = [
                    'Tenda' => 'tent', 'Carrier & Tas' => 'backpack',
                    'Sleeping Gear' => 'sleeping-bag', 'Alat Masak' => 'flame',
                    'Lighting' => 'lantern', 'Perlengkapan Lain' => 'compass',
                ];
            @endphp
            @foreach ($categories as $idx => $category)
                <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="group bg-bone-50 p-8 hover:bg-forest-950 hover:text-bone-50 transition-all relative overflow-hidden">
                    <div class="absolute top-6 right-6 font-mono text-[10px] tracking-[0.2em] text-bone-400 group-hover:text-bone-50/50">
                        {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="w-14 h-14 border border-forest-950 group-hover:border-bone-50 flex items-center justify-center transition-colors">
                        <x-icon :name="$catIcons[$category->name] ?? 'mountain'" class="w-7 h-7 text-forest-950 group-hover:text-bone-50"/>
                    </div>
                    <div class="font-display text-2xl font-medium mt-6 tracking-super-tight">{{ $category->name }}</div>
                    <div class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 group-hover:text-bone-300 mt-2">{{ $category->items_count }} produk tersedia</div>
                    <div class="mt-8 inline-flex items-center gap-2 text-xs font-medium">
                        Lihat semua
                        <x-icon name="arrow-up-right" class="w-3.5 h-3.5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- POPULAR ITEMS -->
<section class="bg-bone-50">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-24">
        <div class="flex items-end justify-between mb-12 flex-wrap gap-6">
            <div>
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-4 flex items-center gap-3">
                    <span class="font-display italic text-2xl text-ember-600" style="font-variation-settings: 'opsz' 60, 'SOFT' 100, 'wght' 400;">ii.</span>
                    Paling Disewa
                </div>
                <h2 class="font-display text-5xl font-medium text-forest-950 tracking-super-tight leading-[0.95]">Sudah teruji jalanan.</h2>
            </div>
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm font-medium text-forest-950 hover:text-forest-700 group">
                Semua produk
                <x-icon name="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"/>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-px bg-bone-200 border border-bone-200">
            @php
                $itemIcons = [
                    'Tenda' => 'tent', 'Carrier & Tas' => 'backpack',
                    'Sleeping Gear' => 'sleeping-bag', 'Alat Masak' => 'flame',
                    'Lighting' => 'lantern', 'Perlengkapan Lain' => 'compass',
                ];
            @endphp
            @foreach ($popularItems as $idx => $item)
                <a href="{{ route('catalog.show', $item) }}" class="group bg-bone-50 hover:bg-bone-100 transition relative">
                    <div class="aspect-[4/5] bg-gradient-to-br from-bone-100 via-bone-50 to-forest-50 relative overflow-hidden border-b border-bone-200">
                        @if ($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <x-icon :name="$itemIcons[$item->category->name] ?? 'mountain'" class="w-24 h-24 text-forest-700/30 group-hover:scale-110 transition-transform duration-500"/>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 font-mono text-[10px] tracking-[0.2em] text-bone-700">
                            {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($popularItems->count(), 2, '0', STR_PAD_LEFT) }}
                        </div>
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
    </div>
</section>

<!-- VALUE PROPS: 3 columns editorial -->
<section class="bg-forest-950 text-bone-50 relative grain">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-24">
        <div class="grid lg:grid-cols-12 gap-10 mb-16">
            <div class="lg:col-span-5">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-400 mb-4 flex items-center gap-3">
                    <span class="font-display italic text-2xl text-ember-400" style="font-variation-settings: 'opsz' 60, 'SOFT' 100, 'wght' 400;">iii.</span>
                    Sistem
                </div>
                <h2 class="font-display text-5xl font-medium tracking-super-tight leading-[0.95]">
                    Dirancang ulang<br/>
                    <em class="italic font-light text-bone-300" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">untuk bekerja.</em>
                </h2>
            </div>
            <div class="lg:col-span-6 lg:col-start-7 self-end">
                <p class="text-base text-bone-300 max-w-md">
                    Pengganti nota tulis tangan dan rekap manual. Sistem ini mengotomatiskan validasi stok,
                    pembuatan invoice, dan perhitungan denda &mdash; supaya owner fokus pada pertumbuhan.
                </p>
            </div>
        </div>

        @php
            $features = [
                ['icon' => 'package-check', 'title' => 'Cek stok otomatis', 'desc' => 'Validasi ketersediaan setiap kali transaksi dibuat. Stok berkurang real-time, mencegah double-booking di sumbernya.'],
                ['icon' => 'file-text', 'title' => 'Invoice digital', 'desc' => 'Setiap transaksi langsung jadi invoice yang bisa dicetak. Riwayat tidak hilang &mdash; cari berdasarkan nama, kode, atau periode.'],
                ['icon' => 'clock', 'title' => 'Denda terhitung sendiri', 'desc' => 'Late_days &times; daily_penalty &times; jumlah item. Tidak bisa di-edit manual &mdash; transparan untuk owner dan pelanggan.'],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-px bg-bone-50/10">
            @foreach ($features as $idx => $f)
                <div class="bg-forest-950 p-8 lg:p-10 relative">
                    <div class="font-mono text-[10px] tracking-[0.2em] text-bone-400 mb-6">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <x-icon :name="$f['icon']" class="w-10 h-10 text-ember-400 mb-6" stroke="1.25"/>
                    <h3 class="font-display text-2xl font-medium tracking-super-tight mb-3">{{ $f['title'] }}</h3>
                    <p class="text-sm text-bone-300 leading-relaxed">{!! $f['desc'] !!}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA: closing editorial -->
<section class="bg-bone-50 border-t border-bone-200">
    <div class="max-w-5xl mx-auto px-6 sm:px-10 lg:px-16 py-24 text-center">
        <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-6">Siap berangkat?</div>
        <h2 class="font-display text-5xl sm:text-6xl lg:text-7xl font-medium text-forest-950 tracking-super-tight leading-[0.95]">
            Gunung tidak pernah<br>
            <em class="italic font-light text-forest-700" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">menunggu siapapun.</em>
        </h2>
        <p class="text-base text-bone-700 max-w-xl mx-auto mt-6">
            Pesan alatmu sekarang. Bayar tunai saat ambil. Kembalikan tepat waktu.
        </p>
        <div class="flex flex-wrap justify-center gap-4 mt-10">
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 text-sm font-medium tracking-wide transition group">
                Lihat Katalog
                <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"/>
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-4 border border-forest-950 text-forest-950 hover:bg-forest-950 hover:text-bone-50 text-sm font-medium tracking-wide transition">
                Buat Akun
            </a>
        </div>
    </div>
</section>
@endsection
