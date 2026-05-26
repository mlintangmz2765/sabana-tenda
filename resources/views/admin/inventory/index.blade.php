@extends('layouts.dashboard')
@section('title', 'Inventaris')
@section('page-title', 'Inventaris Barang')

@section('content')
<div class="bg-white border border-slate-200 rounded-xl p-5 mb-4">
    <form method="GET" class="grid md:grid-cols-5 gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..."
               class="md:col-span-2 px-4 py-2 border border-slate-300 rounded-lg text-sm">
        <select name="category" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="available" @selected(request('status') === 'available')>Tersedia</option>
            <option value="unavailable" @selected(request('status') === 'unavailable')>Habis</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-sabana-700 text-white rounded-lg text-sm font-medium">Filter</button>
            @if (in_array(auth()->user()->role, ['admin','owner']))
                <a href="{{ route('admin.inventory.create') }}" class="px-3 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium">+ Barang</a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Barang</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4 text-center">Stok</th>
                    <th class="py-3 px-4 text-center">Tersedia</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Kondisi</th>
                    <th class="py-3 px-4 text-right">Harga/Hari</th>
                    @if (in_array(auth()->user()->role, ['admin','owner']))
                        <th class="py-3 px-4 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($items as $item)
                    <tr>
                        <td class="py-3 px-4 font-mono text-xs">{{ $item->item_code }}</td>
                        <td class="py-3 px-4 font-medium">
                            <div class="flex items-center gap-2">
                                @php
                                    $catIcons = ['Tenda' => 'tent', 'Carrier & Tas' => 'backpack', 'Sleeping Gear' => 'sleeping-bag', 'Alat Masak' => 'flame', 'Lighting' => 'lantern', 'Perlengkapan Lain' => 'compass'];
                                @endphp
                                <div class="w-8 h-8 rounded-lg bg-bone-100 flex items-center justify-center">
                                    <x-icon :name="$catIcons[$item->category->name ?? ''] ?? 'mountain'" class="w-4 h-4 text-forest-700"/>
                                </div>
                                <div>
                                    <div>{{ $item->name }}</div>
                                    @if (! $item->is_active)
                                        <span class="text-xs text-rose-600">(Nonaktif)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">{{ $item->category->name }}</td>
                        <td class="py-3 px-4 text-center font-semibold">{{ $item->stock }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="font-semibold {{ $item->available_stock === 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $item->available_stock }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if ($item->status === 'available')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset bg-emerald-50 text-emerald-700 ring-emerald-600/20">Tersedia</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset bg-rose-50 text-rose-700 ring-rose-600/20">Habis</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-xs">
                            @if ($item->condition === 'good')
                                <span class="text-emerald-700 inline-flex items-center gap-1"><x-icon name="check-circle" class="w-3.5 h-3.5"/> Baik</span>
                            @elseif ($item->condition === 'minor_damage')
                                <span class="text-amber-700 inline-flex items-center gap-1"><x-icon name="alert-triangle" class="w-3.5 h-3.5"/> Rusak Ringan</span>
                            @else
                                <span class="text-rose-700 inline-flex items-center gap-1"><x-icon name="x-circle" class="w-3.5 h-3.5"/> Rusak Berat</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                        @if (in_array(auth()->user()->role, ['admin','owner']))
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.inventory.edit', $item) }}" class="text-sabana-700 hover:text-sabana-800 text-sm font-medium mr-2">Edit</a>
                                <form method="POST" action="{{ route('admin.inventory.destroy', $item) }}" class="inline" onsubmit="return confirm('Nonaktifkan barang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 text-sm font-medium">Nonaktifkan</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-12 text-slate-500">Tidak ada data barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-200">
        {{ $items->links() }}
    </div>
</div>
@endsection
