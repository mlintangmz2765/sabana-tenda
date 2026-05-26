<footer class="bg-forest-950 text-bone-100 mt-24 relative grain">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-20">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-16">
            <div class="md:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 border border-bone-100/30 flex items-center justify-center">
                        <x-icon name="mountain" class="w-6 h-6 text-bone-50"/>
                    </div>
                    <div>
                        <div class="font-display text-2xl font-semibold text-bone-50 tracking-super-tight">Sabana Tenda</div>
                        <div class="font-mono text-[10px] tracking-[0.2em] text-bone-300 uppercase">Rental Mgmt System</div>
                    </div>
                </div>
                <p class="text-sm text-bone-300 leading-relaxed max-w-md">
                    Penyewaan alat camping lengkap & berkualitas. Sistem terkomputerisasi
                    untuk inventaris dan peminjaman barang yang transparan, akurat, dan dapat dipercaya.
                </p>
                <div class="mt-6 inline-flex items-center gap-3 text-xs text-bone-300 font-mono">
                    <span class="w-2 h-2 rounded-full bg-forest-400 animate-pulse"></span>
                    Sistem online &middot; Update real-time
                </div>
            </div>

            <div class="md:col-span-4">
                <div class="font-mono text-[10px] tracking-[0.2em] text-bone-300 uppercase mb-5">Kontak</div>
                <ul class="space-y-3 text-sm text-bone-100">
                    <li class="flex items-start gap-3"><x-icon name="map-pin" class="w-4 h-4 mt-0.5 text-forest-400"/> {{ $sabanaBusiness['address'] }}</li>
                    <li class="flex items-start gap-3"><x-icon name="phone" class="w-4 h-4 mt-0.5 text-forest-400"/> {{ $sabanaBusiness['phone'] }}</li>
                    <li class="flex items-start gap-3"><x-icon name="mail" class="w-4 h-4 mt-0.5 text-forest-400"/> {{ $sabanaBusiness['email'] }}</li>
                    <li class="flex items-start gap-3"><x-icon name="whatsapp" class="w-4 h-4 mt-0.5 text-forest-400"/> {{ $sabanaBusiness['whatsapp'] }}</li>
                </ul>
            </div>

            <div class="md:col-span-3">
                <div class="font-mono text-[10px] tracking-[0.2em] text-bone-300 uppercase mb-5">Navigasi</div>
                <ul class="space-y-3 text-sm text-bone-100">
                    <li><a href="{{ route('catalog') }}" class="hover:text-bone-50 inline-flex items-center gap-2 group">Katalog <x-icon name="arrow-up-right" class="w-3 h-3 opacity-50 group-hover:opacity-100"/></a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-bone-50 inline-flex items-center gap-2 group">Tentang Sistem <x-icon name="arrow-up-right" class="w-3 h-3 opacity-50 group-hover:opacity-100"/></a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-bone-50 inline-flex items-center gap-2 group">Login Staff <x-icon name="arrow-up-right" class="w-3 h-3 opacity-50 group-hover:opacity-100"/></a></li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-bone-100/10 flex flex-col sm:flex-row justify-between gap-3 text-[11px] font-mono tracking-wider text-bone-400 uppercase">
            <div>&copy; {{ date('Y') }} Sabana Tenda &mdash; Group L</div>
            <div>Sistem Analisis dan Desain &middot; Universitas Gadjah Mada</div>
        </div>
    </div>
</footer>
