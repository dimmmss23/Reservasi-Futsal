@extends('layouts.app')

@section('title', 'Login - Futsal ID')

@section('content')

<section class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-slate-800 rounded-2xl border border-slate-700 p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-neon-green rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-futbol text-slate-900 text-3xl"></i>
                </div>
                <h2 class="font-orbitron text-3xl font-bold mb-2">
                    LOGIN <span class="text-neon-green text-glow">FUTSAL ID</span>
                </h2>
                <p class="text-gray-400">Masuk ke akun Anda</p>
            </div>
            
            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">
                        <i class="fas fa-envelope mr-2 text-neon-green"></i> Email
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required
                           class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:border-neon-green focus:outline-none transition"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-300">
                        <i class="fas fa-lock mr-2 text-neon-green"></i> Password
                    </label>
                    <input type="password" 
                           name="password" 
                           required
                           class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:border-neon-green focus:outline-none transition"
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="remember" 
                           id="remember"
                           class="w-4 h-4 bg-slate-900 border-slate-600 rounded text-neon-green focus:ring-neon-green">
                    <label for="remember" class="ml-2 text-sm text-gray-300">
                        Ingat saya
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-neon-green text-slate-900 py-3 rounded-lg font-bold text-lg hover:glow-green-strong transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-gray-400">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-neon-green hover:underline font-bold">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
