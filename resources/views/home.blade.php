@extends('layouts.app')

@section('title', 'Futsal ID - Booking Lapangan Futsal Terbaik')

@section('content')

<!-- Hero Section -->
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?w=1920&q=80" 
             alt="Futsal Field" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/90 via-slate-900/70 to-slate-900"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center px-4">
        <h1 class="font-orbitron text-6xl md:text-8xl font-black mb-6">
            <span class="text-white">FUTSAL</span> 
            <span class="text-neon-green text-glow">ID</span>
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Sistem Reservasi Lapangan Futsal Modern & Cepat
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" 
                   class="bg-neon-green text-slate-900 px-8 py-4 rounded-lg text-lg font-bold hover:glow-green-strong transition inline-block">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Gratis
                </a>
                <a href="#fields" 
                   class="bg-slate-800 text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-slate-700 border border-neon-green/30 transition inline-block">
                    <i class="fas fa-calendar-check mr-2"></i> Lihat Lapangan
                </a>
            @else
                <a href="{{ route('reservations.index') }}" 
                   class="bg-neon-green text-slate-900 px-8 py-4 rounded-lg text-lg font-bold hover:glow-green-strong transition inline-block">
                    <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                </a>
            @endguest
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-neon-green text-3xl"></i>
    </div>
</section>

<!-- Fields Section -->
<section id="fields" class="py-20 bg-gradient-dark">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="font-orbitron text-4xl md:text-5xl font-bold mb-4">
                LAPANGAN <span class="text-neon-green text-glow">KAMI</span>
            </h2>
            <p class="text-gray-400 text-lg">Pilih lapangan terbaik untuk pertandingan Anda</p>
        </div>
        
        <!-- Fields Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($fields as $field)
                <div class="bg-slate-800 rounded-xl overflow-hidden border border-slate-700 hover-lift hover:border-neon-green/50 transition">
                    <!-- Field Image -->
                    <div class="h-48 bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center overflow-hidden">
                        @if($field->image)
                            <img src="{{ asset('storage/' . $field->image) }}" 
                                 alt="{{ $field->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-futbol text-6xl text-neon-green/30"></i>
                        @endif
                    </div>
                    
                    <!-- Field Info -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-2xl font-bold text-white">{{ $field->name }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                {{ $field->status === 'available' ? 'bg-green-500/20 text-green-400 border border-green-500' : 'bg-red-500/20 text-red-400 border border-red-500' }}">
                                {{ $field->status === 'available' ? 'Buka' : 'Maintenance' }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 text-gray-300 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-layer-group w-5 text-neon-green mr-2"></i>
                                <span>{{ $field->type }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave w-5 text-neon-green mr-2"></i>
                                <span class="font-bold text-white">{{ $field->formatted_price }}</span>
                                <span class="text-sm text-gray-400 ml-1">/jam</span>
                            </div>
                        </div>
                        
                        <!-- Today's Bookings Info -->
                        @if($field->hasBookingsToday && $field->status === 'available')
                            <div class="mb-4 p-3 bg-yellow-900/20 border border-yellow-600 rounded-lg">
                                <p class="text-yellow-400 text-xs font-semibold mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Ada {{ $field->todayBookings->count() }} booking hari ini:
                                </p>
                                <div class="space-y-1">
                                    @foreach($field->todayBookings->take(3) as $booking)
                                        <p class="text-yellow-300 text-xs">
                                            • {{ \Carbon\Carbon::parse($booking->book_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($booking->book_time)->addHours($booking->duration)->format('H:i') }}
                                        </p>
                                    @endforeach
                                    @if($field->todayBookings->count() > 3)
                                        <p class="text-yellow-300 text-xs">+ {{ $field->todayBookings->count() - 3 }} lainnya</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @auth
                            @if($field->status === 'available')
                                <a href="{{ route('reservations.create', $field->id) }}" 
                                   class="block w-full bg-neon-green text-slate-900 text-center py-3 rounded-lg font-bold hover:glow-green transition">
                                    <i class="fas fa-calendar-plus mr-2"></i> Cek Ketersediaan & Booking
                                </a>
                            @else
                                <button disabled class="block w-full bg-slate-700 text-gray-400 text-center py-3 rounded-lg font-bold cursor-not-allowed">
                                    <i class="fas fa-wrench mr-2"></i> Sedang Maintenance
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full bg-slate-700 text-white text-center py-3 rounded-lg font-bold hover:bg-slate-600 transition">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Booking
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 text-lg">Belum ada lapangan tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-slate-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="font-orbitron text-4xl font-bold mb-4">
                KENAPA <span class="text-neon-green text-glow">FUTSAL ID?</span>
            </h2>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-8 bg-slate-800 rounded-xl border border-slate-700 hover:border-neon-green/50 transition">
                <div class="w-16 h-16 bg-neon-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bolt text-3xl text-neon-green"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Booking Cepat</h3>
                <p class="text-gray-400">Proses reservasi hanya dalam hitungan detik</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="text-center p-8 bg-slate-800 rounded-xl border border-slate-700 hover:border-neon-green/50 transition">
                <div class="w-16 h-16 bg-neon-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-3xl text-neon-green"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Pembayaran Aman</h3>
                <p class="text-gray-400">Sistem pembayaran terjamin dan terpercaya</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="text-center p-8 bg-slate-800 rounded-xl border border-slate-700 hover:border-neon-green/50 transition">
                <div class="w-16 h-16 bg-neon-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-3xl text-neon-green"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Lapangan Berkualitas</h3>
                <p class="text-gray-400">Fasilitas terbaik dengan harga terjangkau</p>
            </div>
        </div>
    </div>
</section>

@endsection
