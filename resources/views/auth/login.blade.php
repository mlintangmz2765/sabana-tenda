<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &middot; Sabana Tenda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..800,0..100&family=Plus+Jakarta+Sans:wght@300..800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: {
                display: ['"Fraunces"','serif'],
                sans: ['"Plus Jakarta Sans"','system-ui','sans-serif'],
                mono: ['"JetBrains Mono"','monospace'],
            },
            colors: {
                forest: {50:'#f4faf6',100:'#e8f4ec',200:'#d2e8d8',300:'#a4d3b3',400:'#6cb487',500:'#3a9462',600:'#25744f',700:'#1a523c',800:'#15402f',900:'#0f2e22',950:'#0a1f17'},
                bone: {50:'#faf8f3',100:'#f3efe5',200:'#e6dfd0',300:'#d2c7b1',400:'#b8a98a',500:'#9d8a66',700:'#4a4233',900:'#1c1917'},
                ember: {300:'#fcd34d',400:'#f59e0b',500:'#d97706',600:'#b45309',700:'#92400e'},
            },
            letterSpacing: { 'super-tight':'-0.04em' },
        }}};
    </script>
    <style>
        body { font-family: '"Plus Jakarta Sans"', system-ui, sans-serif; }
        .font-display { font-family: 'Fraunces', serif; font-variation-settings: "opsz" 144, "SOFT" 50; }
        .grain::before { content:""; position:absolute; inset:0; pointer-events:none; opacity:.06; mix-blend-mode:multiply; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' /%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); }
        .topo-bg {
            background-color: #0a1f17;
            background-image:
                radial-gradient(ellipse at top, rgba(58, 148, 98, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse at bottom right, rgba(217, 119, 6, 0.12) 0%, transparent 50%);
        }
        @keyframes slowFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .float-slow { animation: slowFloat 6s ease-in-out infinite; }
    </style>
</head>
<body class="topo-bg min-h-screen relative grain">

<!-- Decorative mountain backdrop SVG -->
<svg class="absolute bottom-0 left-0 right-0 w-full text-forest-900/40 pointer-events-none" viewBox="0 0 1200 300" fill="none" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0 300 L0 200 L150 80 L300 180 L450 60 L600 160 L750 40 L900 140 L1050 100 L1200 180 L1200 300 Z" fill="currentColor"/>
</svg>
<svg class="absolute bottom-0 left-0 right-0 w-full text-forest-950/60 pointer-events-none" viewBox="0 0 1200 250" fill="none" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0 250 L0 180 L100 100 L240 170 L360 60 L500 140 L650 90 L800 160 L950 80 L1100 150 L1200 100 L1200 250 Z" fill="currentColor"/>
</svg>

<div class="relative min-h-screen flex items-center justify-center p-4 sm:p-8 z-10">
    <div class="w-full max-w-md">
        <a href="/" class="flex items-center justify-center gap-3 mb-10 text-bone-50 group">
            <div class="w-12 h-12 border border-bone-50/40 flex items-center justify-center float-slow">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 20 L8 11 L12 16 L16 8 L21 20 Z"/>
                    <circle cx="16.5" cy="6" r="1.2" fill="currentColor" stroke="none"/>
                </svg>
            </div>
            <div>
                <div class="font-display text-3xl font-semibold tracking-super-tight">Sabana Tenda</div>
                <div class="font-mono text-[10px] tracking-[0.25em] text-bone-300 uppercase mt-1.5">Rental Management System</div>
            </div>
        </a>

        <div class="bg-bone-50 shadow-2xl border-t-2 border-ember-500">
            <div class="p-10">
                <div class="font-mono text-[10px] tracking-[0.25em] text-ember-600 uppercase mb-3">01 &mdash; Authentication</div>
                <h1 class="font-display text-4xl font-semibold text-forest-950 tracking-super-tight leading-none">Selamat<br>Datang.</h1>
                <p class="text-sm text-bone-700 mt-4">Silakan masuk untuk mengakses sistem peminjaman alat camping.</p>

                @if (session('error'))
                    <div class="mt-6 border-l-2 border-ember-500 bg-ember-300/20 px-4 py-3 text-ember-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="mt-6 border-l-2 border-forest-700 bg-forest-50 px-4 py-3 text-forest-900 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5 mt-8">
                    @csrf
                    <div>
                        <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 block">Username / Email</label>
                        <input type="text" name="username" value="{{ old('username') }}" required autofocus
                               placeholder="owner_sabana"
                               class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none text-base font-medium placeholder-bone-400 transition">
                        @error('username') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700 block">Password</label>
                        <div class="relative mt-2">
                            <input :type="show ? 'text' : 'password'" name="password" required
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                   class="w-full px-0 py-3 pr-10 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none text-base font-medium placeholder-bone-400 transition">
                            <button type="button" @click="show = !show" class="absolute right-0 top-1/2 -translate-y-1/2 text-bone-500 hover:text-forest-700">
                                <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12 C 5 6 9 4 12 4 C 15 4 19 6 22 12 C 19 18 15 20 12 20 C 9 20 5 18 2 12 Z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3 L21 21"/><path d="M10.5 6.2 C 11 6.1 11.5 6 12 6 C 15 6 19 8 22 14"/><path d="M6 9 C 4.5 10.5 3.3 12.2 2 14 C 5 20 9 22 12 22 C 13.5 22 15 21.7 16.5 21"/><path d="M9.9 9.9 a 3 3 0 0 0 4.2 4.2"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-bone-700 select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 border-bone-300 text-forest-700 focus:ring-forest-500">
                        Ingat saya di perangkat ini
                    </label>

                    <button type="submit" class="w-full mt-2 px-5 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 font-medium text-sm tracking-wide transition group inline-flex items-center justify-center gap-3">
                        Masuk ke Sistem
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/>
                        </svg>
                    </button>
                </form>

                <p class="text-xs text-bone-700 mt-7 text-center">
                    Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-forest-700 hover:text-forest-950 underline underline-offset-4">Daftar sebagai customer</a>
                </p>
            </div>

            <div class="border-t border-bone-200 px-10 py-6 bg-bone-100">
                <div class="font-mono text-[10px] tracking-[0.25em] uppercase text-bone-700 mb-3">Akun Demo</div>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    <div><span class="font-mono text-forest-700">owner</span> &middot; <span class="font-mono">owner_sabana</span></div>
                    <div><span class="font-mono text-ember-600">pwd</span> &middot; <span class="font-mono">OwnerSabana123</span></div>
                    <div><span class="font-mono text-forest-700">staff</span> &middot; <span class="font-mono">staff_sabana</span></div>
                    <div><span class="font-mono text-ember-600">pwd</span> &middot; <span class="font-mono">StaffSabana123</span></div>
                    <div><span class="font-mono text-forest-700">customer</span> &middot; <span class="font-mono">budi_customer</span></div>
                    <div><span class="font-mono text-ember-600">pwd</span> &middot; <span class="font-mono">BudiCustomer123</span></div>
                </div>
            </div>
        </div>

        <p class="text-center text-[11px] font-mono tracking-[0.15em] uppercase text-bone-400 mt-8">
            &copy; {{ date('Y') }} Sabana Tenda &middot; Group L
        </p>
    </div>
</div>
</body>
</html>
