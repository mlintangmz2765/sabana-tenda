@extends('layouts.dashboard')
@section('title', 'Pelanggan')
@section('page-title', 'Daftar Pelanggan')

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <form method="GET" class="flex gap-3">
                <input name="search" value="{{ request('search') }}" placeholder="Cari nama / telepon / KTP..."
                       class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium">Cari</button>
            </form>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4">Kode</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Telepon</th>
                    <th class="py-3 px-4">{{ 'KTP' }}</th>
                    <th class="py-3 px-4 text-center">Sewa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($customers as $cust)
                    <tr>
                        <td class="py-3 px-4 font-mono text-xs">{{ $cust->customer_code }}</td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.customers.show', $cust) }}" class="font-medium hover:text-sabana-700">{{ $cust->name }}</a>
                        </td>
                        <td class="py-3 px-4">{{ $cust->phone }}</td>
                        <td class="py-3 px-4 font-mono text-xs">{{ $cust->id_card_number }}</td>
                        <td class="py-3 px-4 text-center font-semibold">{{ $cust->rentals_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-12 text-slate-500">Belum ada pelanggan</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-200">{{ $customers->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="text-base font-semibold text-slate-900 mb-4">Tambah Pelanggan</div>
        <form method="POST" action="{{ route('staff.customers.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm font-medium text-slate-700">Nama</label>
                <input type="text" name="name" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">No. Telepon</label>
                <input type="text" name="phone" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Email (Opsional)</label>
                <input type="email" name="email" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="address" rows="2" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Jenis</label>
                    <select name="id_card_type" class="w-full mt-1 px-2 py-2 border border-slate-300 rounded-lg text-sm">
                        <option value="KTP">KTP</option>
                        <option value="SIM">SIM</option>
                        <option value="Passport">Passport</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-medium text-slate-700">No. ID</label>
                    <input type="text" name="id_card_number" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-sabana-700 hover:bg-sabana-800 text-white text-sm font-semibold rounded-lg">+ Tambah Pelanggan</button>
        </form>
    </div>
</div>
@endsection
