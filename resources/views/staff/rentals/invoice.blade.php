<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $rental->rental_code }}</title>

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
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .font-display { font-family: 'Fraunces', ui-serif, Georgia, serif; font-variation-settings: "opsz" 96, "SOFT" 50; }
        .ticker { font-feature-settings: "tnum"; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-card { box-shadow: none !important; margin: 0 !important; border-radius: 0 !important; }
        }
    </style>
</head>
<body class="bg-bone-100 font-sans text-forest-950 antialiased p-6 sm:p-10">

    <div class="invoice-card max-w-3xl mx-auto bg-white rounded-xl shadow-lg border border-bone-200 overflow-hidden">

        {{-- Header Bar --}}
        <div class="bg-forest-950 text-bone-50 px-8 py-6">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 border border-bone-100/30 flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                            <path d="M3 20 L8 11 L12 16 L16 8 L21 20 Z"/>
                            <circle cx="16.5" cy="6" r="1.2" fill="currentColor" stroke="none"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-display text-xl font-semibold tracking-tight">SABANA Tenda</div>
                        <div class="font-mono text-[10px] tracking-[0.2em] text-bone-300 uppercase">Rental Management System</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-mono text-[10px] tracking-[0.2em] text-bone-400 uppercase">Invoice</div>
                    <div class="font-mono text-sm font-semibold text-bone-50 mt-0.5">{{ $rental->rental_code }}</div>
                </div>
            </div>
        </div>

        {{-- Sub-header: Business Info & Status --}}
        <div class="bg-forest-900 px-8 py-3 flex justify-between items-center text-xs">
            <div class="text-bone-300">
                {{ config('sabana.business.address', 'Jl. Sabana No. 1, Yogyakarta') }}
                <span class="mx-1.5 text-bone-400/40">|</span>
                {{ config('sabana.business.phone', '0812-3456-7890') }}
                <span class="mx-1.5 text-bone-400/40">|</span>
                wa.me/{{ config('sabana.business.whatsapp', '6281234567890') }}
            </div>
            <div>
                @php
                    $statusColors = [
                        'active'    => 'bg-emerald-500',
                        'completed' => 'bg-forest-600',
                        'late'      => 'bg-ember-500',
                        'cancelled' => 'bg-red-500',
                    ];
                    $color = $statusColors[$rental->rental_status] ?? 'bg-bone-400';
                @endphp
                <span class="inline-block px-2.5 py-0.5 {{ $color }} text-white text-[10px] font-bold font-mono tracking-wider uppercase rounded-full">
                    {{ $rental->statusLabel() }}
                </span>
            </div>
        </div>

        <div class="px-8 py-6 space-y-6">

            {{-- Date & Customer Info --}}
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <div class="font-mono text-[10px] tracking-[0.25em] text-bone-500 uppercase mb-2">Pelanggan</div>
                    <div class="font-semibold text-lg text-forest-950">{{ $rental->customer->name }}</div>
                    <div class="text-sm text-bone-700 mt-0.5">{{ $rental->customer->phone }}</div>
                    @if($rental->customer->address)
                        <div class="text-xs text-bone-500 mt-1 leading-relaxed">{{ $rental->customer->address }}</div>
                    @endif
                </div>
                <div>
                    <div class="font-mono text-[10px] tracking-[0.25em] text-bone-500 uppercase mb-2">Periode Sewa</div>
                    <div class="font-semibold text-lg text-forest-950">
                        {{ $rental->rental_date->translatedFormat('d M') }} &ndash; {{ $rental->return_date->translatedFormat('d M Y') }}
                    </div>
                    <div class="text-sm text-bone-700 mt-0.5">{{ $rental->duration_days }} hari</div>
                    <div class="text-xs text-bone-500 mt-1">Dibuat: {{ $rental->created_at->translatedFormat('d M Y, H:i') }}</div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-bone-200"></div>

            {{-- Items Table --}}
            <div class="border border-bone-200 rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-bone-100">
                            <th class="text-left py-3 px-4 font-mono text-[10px] tracking-[0.2em] text-bone-700 uppercase font-semibold">Barang</th>
                            <th class="text-center py-3 px-4 font-mono text-[10px] tracking-[0.2em] text-bone-700 uppercase font-semibold w-16">Qty</th>
                            <th class="text-right py-3 px-4 font-mono text-[10px] tracking-[0.2em] text-bone-700 uppercase font-semibold">Harga/Hari</th>
                            <th class="text-center py-3 px-4 font-mono text-[10px] tracking-[0.2em] text-bone-700 uppercase font-semibold w-16">Hari</th>
                            <th class="text-right py-3 px-4 font-mono text-[10px] tracking-[0.2em] text-bone-700 uppercase font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bone-200">
                        @foreach ($rental->details as $detail)
                            <tr class="hover:bg-bone-50 transition-colors">
                                <td class="py-3 px-4 text-forest-950 font-medium">{{ $detail->item->name }}</td>
                                <td class="text-center py-3 px-4 text-bone-700 ticker">{{ $detail->quantity }}</td>
                                <td class="text-right py-3 px-4 text-bone-700 ticker font-mono text-xs">Rp {{ number_format($detail->price_per_day, 0, ',', '.') }}</td>
                                <td class="text-center py-3 px-4 text-bone-700 ticker">{{ $rental->duration_days }}</td>
                                <td class="text-right py-3 px-4 text-forest-950 font-semibold ticker font-mono text-xs">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Total Row --}}
                <div class="bg-forest-950 text-bone-50 px-4 py-4 flex justify-between items-center">
                    <span class="font-mono text-[10px] tracking-[0.25em] uppercase font-semibold text-bone-300">Total Pembayaran</span>
                    <span class="font-display text-2xl font-bold ticker">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Notes --}}
            @if($rental->notes)
                <div class="bg-bone-50 border border-bone-200 rounded-lg px-4 py-3">
                    <div class="font-mono text-[10px] tracking-[0.2em] text-bone-500 uppercase mb-1">Catatan</div>
                    <div class="text-sm text-bone-700">{{ $rental->notes }}</div>
                </div>
            @endif

            {{-- Footer Info --}}
            <div class="border-t border-bone-200 pt-4 space-y-2">
                <div class="font-mono text-[10px] tracking-[0.2em] text-bone-500 uppercase mb-2">Ketentuan</div>
                <div class="text-xs text-bone-700 flex items-start gap-2">
                    <span class="text-ember-500 mt-0.5">•</span>
                    Denda keterlambatan: <span class="font-semibold">Rp {{ number_format(config('sabana.daily_penalty', 25000), 0, ',', '.') }}/hari per item</span>
                </div>
                <div class="text-xs text-bone-700 flex items-start gap-2">
                    <span class="text-ember-500 mt-0.5">•</span>
                    Kerusakan barang akan dikenakan biaya sesuai estimasi perbaikan
                </div>
                <div class="text-xs text-bone-700 flex items-start gap-2">
                    <span class="text-ember-500 mt-0.5">•</span>
                    Mohon kembalikan barang tepat waktu untuk menghindari denda
                </div>
            </div>

            {{-- Thank You --}}
            <div class="text-center pt-2 pb-1">
                <div class="text-sm font-semibold text-forest-700">Terima kasih telah menyewa di SABANA Tenda!</div>
                <div class="text-xs text-bone-400 mt-1">Invoice ini sah secara digital tanpa tanda tangan basah.</div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="max-w-3xl mx-auto mt-6 flex justify-center gap-3 no-print">
        <button onclick="window.print()" class="px-6 py-2.5 bg-forest-950 text-bone-50 font-semibold text-sm rounded-lg hover:bg-forest-900 transition-colors inline-flex items-center gap-2 shadow-sm">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <path d="M7 8V3h10v5"/>
                <rect x="4" y="8" width="16" height="9" rx="1.5"/>
                <rect x="7" y="14" width="10" height="6"/>
                <circle cx="17" cy="11" r="0.7" fill="currentColor" stroke="none"/>
            </svg>
            Cetak Invoice
        </button>
        <a href="{{ route('staff.rentals.show', $rental) }}" class="px-6 py-2.5 bg-white text-forest-950 font-semibold text-sm rounded-lg border border-bone-200 hover:bg-bone-50 transition-colors shadow-sm">
            &larr; Kembali
        </a>
    </div>

</body>
</html>
