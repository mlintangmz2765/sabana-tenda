<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; SABANA Tenda</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..800,0..100&family=Plus+Jakarta+Sans:wght@300..800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                },
            },
        };
    </script>
    <style>
        body { font-family: '"Plus Jakarta Sans"', system-ui, sans-serif; }
        .font-display { font-family: 'Fraunces', ui-serif, Georgia, serif; font-variation-settings: "opsz" 96, "SOFT" 50; }
        .font-mono { font-feature-settings: 'zero'; }
        .ticker { font-feature-settings: "tnum"; }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: #f3efe5; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #b8a98a; border-radius: 3px; }
        [x-cloak] { display: none !important; }
        .card-stack { background: white; border: 1px solid #e6dfd0; }
    </style>
    @stack('head')
</head>
<body class="font-sans bg-bone-50 text-forest-950 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        @include('partials.sidebar')

        <div class="flex-1 lg:ml-72 flex flex-col min-h-screen">
            @include('partials.topbar')

            <main class="flex-1 p-5 sm:p-8 lg:p-10">
                @if (session('success'))
                    <div class="mb-5 border border-forest-700 bg-forest-50 px-5 py-4 text-forest-900 text-sm flex items-start gap-3">
                        <x-icon name="check-circle" class="w-5 h-5 mt-0.5 text-forest-700"/>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-5 border border-ember-600 bg-ember-300/30 px-5 py-4 text-ember-700 text-sm flex items-start gap-3">
                        <x-icon name="alert-triangle" class="w-5 h-5 mt-0.5"/>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 border border-ember-600 bg-ember-300/30 px-5 py-4 text-ember-700 text-sm">
                        <div class="flex items-start gap-3">
                            <x-icon name="alert-triangle" class="w-5 h-5 mt-0.5"/>
                            <div>
                                <div class="font-semibold mb-1">Mohon koreksi input berikut:</div>
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
