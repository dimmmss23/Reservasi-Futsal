<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Futsal ID - Sistem Reservasi Lapangan Futsal')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        neon: {
                            green: '#39FF14',
                            blue: '#00FFFF',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Rajdhani', sans-serif;
        }
        
        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        
        .glow-green {
            box-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
        }
        
        .glow-green-strong {
            box-shadow: 0 0 30px rgba(57, 255, 20, 0.8), 0 0 60px rgba(57, 255, 20, 0.4);
        }
        
        .text-glow {
            text-shadow: 0 0 10px rgba(57, 255, 20, 0.8);
        }
        
        .bg-gradient-dark {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-slate-900 text-gray-100 min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-slate-950 border-b border-neon-green/20 sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-neon-green rounded-lg flex items-center justify-center">
                        <i class="fas fa-futbol text-slate-900 text-xl"></i>
                    </div>
                    <span class="font-orbitron text-2xl font-bold text-glow">FUTSAL <span class="text-neon-green">ID</span></span>
                </a>
                
                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="hover:text-neon-green transition">Beranda</a>
                    
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-neon-green transition">Dashboard Admin</a>
                        @else
                            <a href="{{ route('reservations.index') }}" class="hover:text-neon-green transition">Reservasi Saya</a>
                        @endif
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-red-400 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-neon-green transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-neon-green text-slate-900 px-4 py-2 rounded-lg font-bold hover:glow-green transition">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-neon-green">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-neon-green/20 mt-20">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <p class="text-gray-400">
                    &copy; 2025 <span class="text-neon-green font-bold">Futsal ID</span>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
