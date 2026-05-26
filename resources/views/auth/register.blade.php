<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar &middot; Sabana Tenda</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..800,0..100&family=Plus+Jakarta+Sans:wght@300..800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: {
                display: ['"Fraunces"','serif'],
                sans: ['"Plus Jakarta Sans"','system-ui','sans-serif'],
                mono: ['"JetBrains Mono"','monospace'],
            },
            colors: {
                forest: {50:'#f4faf6',100:'#e8f4ec',300:'#a4d3b3',500:'#3a9462',700:'#1a523c',800:'#15402f',900:'#0f2e22',950:'#0a1f17'},
                bone: {50:'#faf8f3',100:'#f3efe5',200:'#e6dfd0',300:'#d2c7b1',400:'#b8a98a',500:'#9d8a66',700:'#4a4233',900:'#1c1917'},
                ember: {500:'#d97706',600:'#b45309'},
            },
            letterSpacing: { 'super-tight':'-0.04em' },
        }}};
    </script>
    <style>
        body { font-family: '"Plus Jakarta Sans"', system-ui, sans-serif; }
        .font-display { font-family: 'Fraunces', serif; font-variation-settings: "opsz" 144, "SOFT" 50; }
    </style>
</head>
<body class="bg-bone-50 min-h-screen">
<div class="min-h-screen grid lg:grid-cols-2">
    <div class="hidden lg:block relative bg-forest-950 text-bone-50 overflow-hidden">
        <svg class="absolute bottom-0 left-0 right-0 w-full text-forest-800" viewBox="0 0 800 400" fill="currentColor" preserveAspectRatio="none">
            <path d="M0 400 L0 250 L100 150 L200 230 L320 80 L450 200 L580 100 L720 200 L800 150 L800 400 Z"/>
        </svg>
        <div class="absolute inset-0 p-12 flex flex-col justify-between">
            <a href="/" class="inline-flex items-center gap-3 self-start">
                <div class="w-11 h-11 border border-bone-50/40 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20 L8 11 L12 16 L16 8 L21 20 Z"/><circle cx="16.5" cy="6" r="1.2" fill="currentColor" stroke="none"/></svg>
                </div>
                <div>
                    <div class="font-display text-2xl font-semibold tracking-super-tight">Sabana</div>
                    <div class="font-mono text-[10px] tracking-[0.25em] text-bone-300 uppercase">Tenda</div>
                </div>
            </a>
            <div>
                <div class="font-mono text-[10px] tracking-[0.25em] uppercase text-ember-500 mb-4">02 &mdash; New Account</div>
                <h2 class="font-display text-5xl font-medium leading-[0.95] tracking-super-tight">Petualanganmu<br/>dimulai dari<br/><em class="font-display italic text-ember-500" style="font-variation-settings: 'opsz' 144, 'SOFT' 100, 'wght' 300;">satu langkah.</em></h2>
                <p class="text-bone-300 mt-6 max-w-md text-sm leading-relaxed">Buat akun customer untuk melihat riwayat sewa pribadi, dapat notifikasi promo, dan booking lebih cepat via WhatsApp.</p>
            </div>
            <div class="flex items-center gap-3 text-bone-400 text-xs">
                <span class="w-12 h-px bg-bone-400/40"></span>
                <span class="font-mono tracking-[0.2em] uppercase">Yogyakarta &middot; Est. 2020</span>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center p-8 lg:p-16">
        <div class="w-full max-w-md">
            <a href="/" class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-11 h-11 border border-forest-950 flex items-center justify-center text-forest-950">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20 L8 11 L12 16 L16 8 L21 20 Z"/></svg>
                </div>
                <div class="font-display text-2xl font-semibold">Sabana</div>
            </a>

            <h1 class="font-display text-3xl font-semibold text-forest-950 tracking-super-tight">Daftar akun baru.</h1>
            <p class="text-sm text-bone-700 mt-2">Isi data berikut untuk membuat akun customer.</p>

            <form method="POST" action="{{ route('register.attempt') }}" class="space-y-5 mt-8">
                @csrf
                <div>
                    <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none">
                    @error('name') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none">
                        @error('username') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none">
                        @error('phone') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none">
                    @error('email') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Password</label>
                        <input type="password" name="password" required class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none">
                        @error('password') <p class="text-xs text-ember-600 mt-2">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-mono text-[10px] tracking-[0.2em] uppercase text-bone-700">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required class="w-full mt-2 px-0 py-3 bg-transparent border-b-2 border-bone-300 focus:border-forest-700 focus:outline-none">
                    </div>
                </div>
                <p class="text-xs text-bone-500">Min. 8 karakter, kombinasi huruf &amp; angka.</p>

                <button type="submit" class="w-full mt-4 px-5 py-4 bg-forest-950 hover:bg-forest-800 text-bone-50 font-medium text-sm tracking-wide transition inline-flex items-center justify-center gap-3 group">
                    Buat Akun
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/>
                    </svg>
                </button>
            </form>

            <p class="text-xs text-bone-700 mt-7 text-center">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-forest-700 hover:text-forest-950 underline underline-offset-4">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
