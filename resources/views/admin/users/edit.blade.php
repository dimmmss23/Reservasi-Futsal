@extends('layouts.admin')

@section('title', 'Edit User - Admin Futsal ID')

@section('content')

<div class="container-fluid px-4 py-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="text-neon-green hover:underline mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar User
        </a>
        <h1 class="text-3xl font-bold text-white mb-2">
            <i class="fas fa-user-edit mr-3 text-neon-green"></i>
            Edit User
        </h1>
        <p class="text-gray-400">Update informasi user: <strong class="text-white">{{ $user->name }}</strong></p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 bg-slate-900 border-b border-slate-700">
            <h3 class="text-lg font-bold text-white">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Informasi User
            </h3>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Nama Lengkap <span class="text-red-400">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $user->name) }}"
                       required
                       class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                       placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Email <span class="text-red-400">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}"
                       required
                       class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                       placeholder="user@example.com">
                @error('email')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    No. Telepon
                </label>
                <input type="text" 
                       name="phone" 
                       value="{{ old('phone', $user->phone) }}"
                       class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                       placeholder="+62 812-3456-7890">
                @error('phone')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Role <span class="text-red-400">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 bg-slate-900 border-2 {{ old('role', $user->role) === 'member' ? 'border-green-500' : 'border-slate-700' }} rounded-lg cursor-pointer hover:border-green-500 transition">
                        <input type="radio" 
                               name="role" 
                               value="member" 
                               {{ old('role', $user->role) === 'member' ? 'checked' : '' }}
                               class="mr-3">
                        <div>
                            <p class="text-white font-semibold">
                                <i class="fas fa-user text-green-400 mr-2"></i>Member
                            </p>
                            <p class="text-xs text-gray-400">Pelanggan yang bisa booking lapangan</p>
                        </div>
                    </label>
                    
                    <label class="relative flex items-center p-4 bg-slate-900 border-2 {{ old('role', $user->role) === 'admin' ? 'border-purple-500' : 'border-slate-700' }} rounded-lg cursor-pointer hover:border-purple-500 transition">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                               class="mr-3">
                        <div>
                            <p class="text-white font-semibold">
                                <i class="fas fa-shield-alt text-purple-400 mr-2"></i>Admin
                            </p>
                            <p class="text-xs text-gray-400">Pengelola sistem dengan akses penuh</p>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Section -->
            <div class="mb-6 p-4 bg-yellow-900/20 border-2 border-yellow-500 rounded-lg">
                <div class="flex items-start mb-4">
                    <i class="fas fa-key text-yellow-400 text-xl mr-3 mt-1"></i>
                    <div>
                        <p class="text-yellow-400 font-bold mb-1">Update Password (Opsional)</p>
                        <p class="text-sm text-gray-300">Biarkan kosong jika tidak ingin mengubah password</p>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">
                        Password Baru
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                           placeholder="Minimal 6 karakter (kosongkan jika tidak diubah)">
                    @error('password')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 pt-4 border-t border-slate-700">
                <button type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold transition shadow-lg hover:shadow-green-500/50">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg font-bold transition text-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- User Info -->
    <div class="mt-6 bg-slate-800 border border-slate-700 rounded-lg p-4">
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 mb-1">User ID</p>
                <p class="text-white font-semibold">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-1">Terdaftar Sejak</p>
                <p class="text-white font-semibold">{{ $user->created_at->format('d M Y, H:i') }}</p>
            </div>
            @if($user->role === 'member')
                <div>
                    <p class="text-gray-400 mb-1">Total Reservasi</p>
                    <p class="text-white font-semibold">{{ $user->reservations->count() }} booking</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-1">Points</p>
                    <p class="text-white font-semibold">{{ $user->points ?? 0 }} pts</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Warning Box -->
    @if($user->id === Auth::id())
    <div class="mt-6 bg-yellow-900/20 border-2 border-yellow-500 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
            <div class="text-sm text-gray-300">
                <p class="font-semibold text-yellow-400 mb-2">Perhatian!</p>
                <p>Anda sedang mengedit akun Anda sendiri. Berhati-hatilah saat mengubah role atau password.</p>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
