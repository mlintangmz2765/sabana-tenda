@extends('layouts.dashboard')
@section('title', $user->exists ? 'Edit User' : 'Tambah User')
@section('page-title', $user->exists ? 'Edit User' : 'Tambah User Baru')

@section('content')
<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="max-w-3xl">
    @csrf
    @if ($user->exists) @method('PUT') @endif

    <div class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Role</label>
                <select name="role" required class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="owner" @selected(old('role', $user->role) === 'owner')>Owner</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                    <option value="customer" @selected(old('role', $user->role) === 'customer')>Customer</option>
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Password {{ $user->exists ? '(kosongkan jika tidak diubah)' : '' }}</label>
                <input type="password" name="password" {{ $user->exists ? '' : 'required' }} class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full mt-1 px-4 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded text-sabana-600">
            Akun Aktif
        </label>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Batal</a>
        <button type="submit" class="px-6 py-2 bg-sabana-700 hover:bg-sabana-800 text-white text-sm font-semibold rounded-lg shadow">
            {{ $user->exists ? 'Simpan Perubahan' : 'Tambah User' }}
        </button>
    </div>
</form>
@endsection
