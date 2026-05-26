@extends('layouts.dashboard')
@section('title', 'User & Akses')
@section('page-title', 'Manajemen User')

@section('content')
<div class="bg-white border border-slate-200 rounded-xl p-5 mb-4">
    <form method="GET" class="grid md:grid-cols-5 gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari nama / username..."
               class="md:col-span-2 px-4 py-2 border border-slate-300 rounded-lg text-sm">
        <select name="role" class="px-4 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Semua Role</option>
            <option value="owner" @selected(request('role') === 'owner')>Owner</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            <option value="staff" @selected(request('role') === 'staff')>Staff</option>
            <option value="customer" @selected(request('role') === 'customer')>Customer</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium">Filter</button>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-sabana-700 text-white rounded-lg text-sm font-medium text-center">+ User Baru</a>
    </form>
</div>

<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <th class="py-3 px-4">Kode</th>
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Username</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Role</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Last Login</th>
                <th class="py-3 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($users as $u)
                <tr>
                    <td class="py-3 px-4 font-mono text-xs">{{ $u->user_code }}</td>
                    <td class="py-3 px-4 font-medium">{{ $u->name }}</td>
                    <td class="py-3 px-4 font-mono text-xs">{{ $u->username }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $u->email }}</td>
                    <td class="py-3 px-4">
                        @php
                            $roleColors = [
                                'owner' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                'admin' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'staff' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'customer' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $roleColors[$u->role] ?? '' }}">
                            {{ $u->roleLabel() }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @if ($u->is_active)
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 bg-rose-50 text-rose-700 text-xs font-medium rounded-full">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-xs text-slate-600">{{ $u->last_login_at?->diffForHumans() ?? '—' }}</td>
                    <td class="py-3 px-4 text-right">
                        <a href="{{ route('admin.users.edit', $u) }}" class="text-sabana-700 hover:text-sabana-800 text-sm font-medium">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 border-t border-slate-200">{{ $users->links() }}</div>
</div>
@endsection
