<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $rental->rental_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> @media print { .no-print { display: none; } body { background: white; } } </style>
</head>
<body class="bg-slate-100 font-sans p-8">
    <div class="max-w-3xl mx-auto bg-gradient-to-br from-sabana-800 to-sabana-900 text-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-white text-sabana-800 flex items-center justify-center"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M3 20 L8 11 L12 16 L16 8 L21 20 Z"/><circle cx="16.5" cy="6" r="1.2" fill="currentColor" stroke="none"/></svg></div>
                        <div>
                            <div class="text-2xl font-extrabold">SABANA Tenda</div>
                            <div class="text-xs text-sabana-200">Rental Management System</div>
                        </div>
                    </div>
                    <div class="text-xs text-sabana-200">{{ config('sabana.business.address') }}</div>
                    <div class="text-xs text-sabana-200">{{ config('sabana.business.phone') }} · {{ config('sabana.business.whatsapp') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">INVOICE</div>
                    <div class="text-sm mt-1">No: {{ $rental->rental_code }}</div>
                    <div class="text-xs text-sabana-200">{{ $rental->created_at->format('d M Y H:i') }}</div>
                    <span class="inline-block mt-2 px-3 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full">
                        {{ strtoupper($rental->statusLabel()) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="text-xs text-sabana-300 uppercase font-semibold mb-2">Pelanggan</div>
                    <div class="font-bold text-lg">{{ $rental->customer->name }}</div>
                    <div class="text-sm text-sabana-200">{{ $rental->customer->phone }}</div>
                    <div class="text-xs text-sabana-200 mt-1">{{ $rental->customer->address }}</div>
                </div>
                <div>
                    <div class="text-xs text-sabana-300 uppercase font-semibold mb-2">Periode Sewa</div>
                    <div class="font-bold text-lg">{{ $rental->rental_date->translatedFormat('d M') }} – {{ $rental->return_date->translatedFormat('d M Y') }}</div>
                    <div class="text-sm text-sabana-200">{{ $rental->duration_days }} hari</div>
                </div>
            </div>

            <div class="bg-sabana-950/40 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-sabana-700/50 text-xs uppercase">
                            <th class="text-left py-3 px-4">Barang</th>
                            <th class="text-center py-3 px-4">Qty</th>
                            <th class="text-right py-3 px-4">Harga/Hari</th>
                            <th class="text-center py-3 px-4">Hari</th>
                            <th class="text-right py-3 px-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rental->details as $detail)
                            <tr class="border-b border-sabana-700/30">
                                <td class="py-3 px-4">{{ $detail->item->name }}</td>
                                <td class="text-center py-3 px-4">{{ $detail->quantity }}</td>
                                <td class="text-right py-3 px-4">Rp {{ number_format($detail->price_per_day, 0, ',', '.') }}</td>
                                <td class="text-center py-3 px-4">{{ $rental->duration_days }}</td>
                                <td class="text-right py-3 px-4 font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-sabana-700/30">
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-right font-semibold">TOTAL</td>
                            <td class="py-4 px-4 text-right text-2xl font-bold text-sabana-200">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 text-xs text-sabana-200 space-y-1">
                <div>• Denda keterlambatan: Rp {{ number_format(config('sabana.daily_penalty'), 0, ',', '.') }}/hari per item</div>
                <div>• Kerusakan barang akan dikenakan biaya sesuai estimasi perbaikan</div>
                <div>• Mohon kembalikan barang tepat waktu untuk menghindari denda</div>
                <div class="pt-3 text-sabana-100 font-semibold">Terima kasih telah menyewa di SABANA Tenda!</div>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto mt-6 flex justify-center gap-3 no-print">
        <button onclick="window.print()" class="px-6 py-2.5 bg-sabana-700 text-white font-semibold rounded-lg hover:bg-sabana-800 inline-flex items-center gap-2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M7 8 V3 H17 V8"/><rect x="4" y="8" width="16" height="9" rx="1.5"/><rect x="7" y="14" width="10" height="6"/><circle cx="17" cy="11" r="0.7" fill="currentColor" stroke="none"/></svg> Cetak Invoice</button>
        <a href="{{ route('staff.rentals.show', $rental) }}" class="px-6 py-2.5 bg-slate-700 text-white font-semibold rounded-lg hover:bg-slate-800">← Kembali</a>
    </div>
</body>
</html>
