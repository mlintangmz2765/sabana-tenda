@extends('layouts.dashboard')
@section('title', 'Kategori')
@section('page-title', 'Kategori Barang')

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <div class="text-base font-semibold text-slate-900">Daftar Kategori</div>
            <div class="text-xs text-slate-500">Kelola kategori barang inventaris</div>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Deskripsi</th>
                    <th class="py-3 px-4 text-center">Jumlah Item</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($categories as $cat)
                    <tr>
                        <td class="py-3 px-4 font-mono text-xs">{{ $cat->category_code }}</td>
                        <td class="py-3 px-4 font-medium">{{ $cat->icon }} {{ $cat->name }}</td>
                        <td class="py-3 px-4 text-slate-600 truncate max-w-xs">{{ $cat->description }}</td>
                        <td class="py-3 px-4 text-center font-semibold">{{ $cat->items_count }}</td>
                        <td class="py-3 px-4 text-right">
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-700 text-sm font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-base font-semibold text-slate-900 mb-4">Tambah Kategori</div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm font-medium text-slate-700">Nama</label>
                <input type="text" name="name" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Icon (opsional)</label>
                <input type="text" name="icon" maxlength="10" placeholder="tent" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-sabana-700 hover:bg-sabana-800 text-white text-sm font-semibold rounded-lg">+ Tambah Kategori</button>
        </form>
    </div>
</div>
@endsection
