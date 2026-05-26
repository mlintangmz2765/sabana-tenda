<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SABANA Tenda') &middot; Rental Alat Camping</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..800,0..100&family=Plus+Jakarta+Sans:wght@300..800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Fraunces"', 'ui-serif', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    },
                    colors: {
                        forest: {
                            50: '#f4faf6', 100: '#e8f4ec', 200: '#d2e8d8',
                            300: '#a4d3b3', 400: '#6cb487', 500: '#3a9462',
                            600: '#25744f', 700: '#1a523c', 800: '#15402f',
                            900: '#0f2e22', 950: '#0a1f17',
                        },
                        bone: {
                            50: '#faf8f3', 100: '#f3efe5', 200: '#e6dfd0',
                            300: '#d2c7b1', 400: '#b8a98a', 500: '#9d8a66',
                            700: '#4a4233', 900: '#1c1917',
                        },
                        ember: {
                            300: '#fcd34d', 400: '#f59e0b', 500: '#d97706',
                            600: '#b45309', 700: '#92400e',
                        },
                    },
                    letterSpacing: {
                        'super-tight': '-0.04em',
                    },
                },
            },
        };
    </script>
    <style>
        :root { --paper: #faf8f3; --ink: #0a1f17; }
        body {
            font-family: '"Plus Jakarta Sans"', system-ui, sans-serif;
            font-feature-settings: 'ss01', 'ss02', 'cv11';
        }
        .font-display { font-family: 'Fraunces', ui-serif, Georgia, serif; font-variation-settings: "opsz" 144, "SOFT" 50; }
        .font-display-soft { font-family: 'Fraunces', ui-serif, Georgia, serif; font-variation-settings: "opsz" 96, "SOFT" 100, "wght" 400; }
        .font-mono { font-feature-settings: 'ss01', 'zero'; }
        .grain { position: relative; }
        .grain::before {
            content:""; position:absolute; inset:0; pointer-events:none; opacity:.04; mix-blend-mode:multiply;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' /%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }
        .topo {
            background-image:
                radial-gradient(circle at 20% 80%, rgba(58, 148, 98, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(217, 119, 6, 0.05) 0%, transparent 50%);
        }
        [x-cloak] { display: none !important; }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: #f3efe5; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #b8a98a; border-radius: 3px; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        .reveal { animation: fadeInUp .8s cubic-bezier(.2,.6,.2,1) both; }
        .reveal-2 { animation: fadeInUp .8s cubic-bezier(.2,.6,.2,1) .12s both; }
        .reveal-3 { animation: fadeInUp .8s cubic-bezier(.2,.6,.2,1) .24s both; }
        .reveal-4 { animation: fadeInUp .8s cubic-bezier(.2,.6,.2,1) .36s both; }
        .ticker { font-feature-settings: "tnum"; }
        .border-grid { background-image: linear-gradient(to right, rgba(10,31,23,.05) 1px, transparent 1px); background-size: 80px 100%; }
    </style>
    @stack('head')
</head>
<body class="bg-bone-50 text-forest-950 antialiased">
    @include('partials.public-nav')

    <main class="min-h-[calc(100vh-5rem)]">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-6">
                <div class="rounded-none border border-forest-700 bg-forest-50 px-5 py-4 text-forest-900 text-sm flex items-start gap-3">
                    <x-icon name="check-circle" class="w-5 h-5 mt-0.5 text-forest-700"/>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-6">
                <div class="rounded-none border border-ember-600 bg-ember-300/30 px-5 py-4 text-ember-700 text-sm flex items-start gap-3">
                    <x-icon name="alert-triangle" class="w-5 h-5 mt-0.5"/>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
