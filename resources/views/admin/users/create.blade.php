@extends('layouts.admin')

@section('title', 'Tambah User - Admin Futsal ID')

@section('content')

<div class="container-fluid px-4 py-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="text-neon-green hover:underline mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar User
        </a>
        <h1 class="text-3xl font-bold text-white mb-2">
            <i class="fas fa-user-plus mr-3 text-neon-green"></i>
            Tambah User Baru
        </h1>
        <p class="text-gray-400">Tambahkan user baru (Admin atau Member) ke dalam sistem</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 bg-slate-900 border-b border-slate-700">
            <h3 class="text-lg font-bold text-white">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Informasi User
            </h3>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Nama Lengkap <span class="text-red-400">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}"
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
                       value="{{ old('email') }}"
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
                       value="{{ old('phone') }}"
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
                    <label class="relative flex items-center p-4 bg-slate-900 border-2 border-slate-700 rounded-lg cursor-pointer hover:border-green-500 transition">
                        <input type="radio" 
                               name="role" 
                               value="member" 
                               {{ old('role', 'member') === 'member' ? 'checked' : '' }}
                               class="mr-3">
                        <div>
                            <p class="text-white font-semibold">
                                <i class="fas fa-user text-green-400 mr-2"></i>Member
                            </p>
                            <p class="text-xs text-gray-400">Pelanggan yang bisa booking lapangan</p>
                        </div>
                    </label>
                    
                    <label class="relative flex items-center p-4 bg-slate-900 border-2 border-slate-700 rounded-lg cursor-pointer hover:border-purple-500 transition">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               {{ old('role') === 'admin' ? 'checked' : '' }}
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

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Password <span class="text-red-400">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                       placeholder="Minimal 6 karakter">
                @error('password')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Konfirmasi Password <span class="text-red-400">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       required
                       class="w-full bg-slate-900 border-2 border-slate-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition"
                       placeholder="Ulangi password">
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 pt-4 border-t border-slate-700">
                <button type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold transition shadow-lg hover:shadow-green-500/50">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg font-bold transition text-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-900/20 border-2 border-blue-500 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-400 text-xl mr-3 mt-1"></i>
            <div class="text-sm text-gray-300">
                <p class="font-semibold text-blue-400 mb-2">Catatan Penting:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Email harus unik dan belum terdaftar di sistem</li>
                    <li>Password minimal 6 karakter untuk keamanan</li>
                    <li>Role Admin memiliki akses penuh ke sistem</li>
                    <li>Role Member hanya bisa melakukan booking dan melihat riwayat booking</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
