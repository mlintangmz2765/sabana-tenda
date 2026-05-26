@extends('layouts.app')
@section('title', 'Tentang Kami')

@section('content')
<section class="bg-forest-950 text-bone-50 grain relative">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-20 lg:py-28">
        <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-400 mb-4 flex items-center gap-3">
            <span class="w-10 h-px bg-ember-400"></span>
            Tentang Kami
        </div>
        <h1 class="font-display text-5xl lg:text-7xl font-medium tracking-super-tight leading-[0.9] max-w-3xl">
            UMKM yang ingin<br>
            <em class="italic font-light text-bone-300" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">tumbuh bersama sistem.</em>
        </h1>
    </div>
</section>

<section class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-16 py-20">
    <div class="grid lg:grid-cols-12 gap-10 mb-16">
        <aside class="lg:col-span-3">
            <div class="sticky top-28 font-mono text-[10px] tracking-[0.25em] uppercase text-bone-700">
                <div class="text-forest-950 mb-3">Daftar Isi</div>
                <ul class="space-y-2">
                    <li><a href="#cerita" class="hover:text-forest-950">01 &middot; Cerita</a></li>
                    <li><a href="#masalah" class="hover:text-forest-950">02 &middot; Masalah</a></li>
                    <li><a href="#solusi" class="hover:text-forest-950">03 &middot; Solusi</a></li>
                    <li><a href="#tim" class="hover:text-forest-950">04 &middot; Tim</a></li>
                </ul>
            </div>
        </aside>

        <div class="lg:col-span-9 space-y-16">
            <article id="cerita">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-3">01 &middot; Cerita</div>
                <h2 class="font-display text-4xl font-medium tracking-super-tight text-forest-950 leading-tight">Dari nota tulis tangan, ke sistem terkomputerisasi.</h2>
                <div class="prose prose-slate max-w-none mt-6 text-base text-bone-900 leading-relaxed">
                    <p>Sabana Tenda berdiri sejak 2020 sebagai UMKM penyewaan alat camping di Yogyakarta. Layanan mencakup tenda, sleeping bag, kompor, carrier, lampu, dan perlengkapan outdoor lainnya untuk komunitas pendaki, keluarga, dan pemula yang ingin menjajal alam.</p>
                    <p>Selama bertahun-tahun, semua operasional dijalankan secara manual: pesanan masuk via WhatsApp atau pelanggan datang langsung, stok dicatat di buku, transaksi ditulis di nota, dan rekap akhir periode disusun di spreadsheet.</p>
                </div>
            </article>

            <article id="masalah">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-3">02 &middot; Masalah</div>
                <h2 class="font-display text-4xl font-medium tracking-super-tight text-forest-950 leading-tight">Catatan fisik yang tidak sinkron.</h2>
                <p class="text-base text-bone-900 mt-6 leading-relaxed">
                    Pemilik dan tim Sabana menemui beberapa kendala yang bertahun-tahun mengganggu efisiensi:
                </p>
                <ul class="mt-6 space-y-3 text-sm">
                    @foreach ([
                        ['Pencatatan manual', 'Human error tinggi, nota sulit dibaca, data sering raib.'],
                        ['Stok tidak real-time', 'Double-booking terjadi karena tidak ada single source of truth.'],
                        ['Tracking barang sulit', 'Barang hilang baru ketahuan jauh setelah kejadian.'],
                        ['Rekap dan denda manual', 'Lambat dan rawan salah hitung — terutama saat ramai.'],
                    ] as $row)
                        <li class="flex gap-4 border-l-2 border-ember-500 pl-5 py-2">
                            <div>
                                <div class="font-medium text-forest-950">{{ $row[0] }}</div>
                                <div class="text-bone-700">{{ $row[1] }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </article>

            <article id="solusi">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-3">03 &middot; Solusi</div>
                <h2 class="font-display text-4xl font-medium tracking-super-tight text-forest-950 leading-tight">Otomatisasi yang menyatu dengan kerja sehari-hari.</h2>
                <p class="text-base text-bone-900 mt-6 leading-relaxed">
                    Sistem ini dirancang untuk menjawab masalah-masalah di atas dengan empat prinsip:
                </p>
                <div class="grid sm:grid-cols-2 gap-px bg-bone-200 mt-8 border border-bone-200">
                    @foreach ([
                        ['package-check', 'Validasi stok otomatis', 'Setiap transaksi mengecek ketersediaan langsung sebelum disimpan.'],
                        ['shield-check', 'Cegah double booking', 'Stok berkurang real-time saat transaksi disimpan.'],
                        ['clock', 'Denda otomatis', 'Late_days &times; daily_penalty &times; item — tidak dapat dimanipulasi.'],
                        ['chart-line', 'Dashboard real-time', 'Owner memantau pendapatan, late returns, dan inventaris kapan saja.'],
                    ] as $row)
                        <div class="bg-bone-50 p-6">
                            <x-icon :name="$row[0]" class="w-7 h-7 text-forest-700 mb-3"/>
                            <div class="font-display text-xl font-medium text-forest-950 tracking-super-tight">{{ $row[1] }}</div>
                            <div class="text-sm text-bone-700 mt-2">{!! $row[2] !!}</div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article id="tim">
                <div class="font-mono text-[11px] tracking-[0.25em] uppercase text-ember-600 mb-3">04 &middot; Tim Pengembang</div>
                <h2 class="font-display text-4xl font-medium tracking-super-tight text-forest-950 leading-tight">Group L &mdash; Universitas Gadjah Mada.</h2>
                <p class="text-base text-bone-900 mt-6 leading-relaxed">
                    Sistem ini dikembangkan sebagai bagian dari mata kuliah Analisis dan Desain Sistem oleh tiga mahasiswa Fakultas Ekonomika dan Bisnis UGM:
                </p>
                <div class="mt-6 space-y-px border-t border-bone-200">
                    @foreach ([
                        ['Destiana Wicaksani', '24/536157/EK/24971'],
                        ['Love\'s Nurani Hasan', '24/533831/EK/24890'],
                        ['M Lintang Maulana Zulfan', '24/539064/EK/25105'],
                    ] as $member)
                        <div class="flex items-baseline justify-between border-b border-bone-200 py-4">
                            <div class="font-display text-xl font-medium text-forest-950 tracking-super-tight">{{ $member[0] }}</div>
                            <div class="font-mono text-xs text-bone-700">{{ $member[1] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
