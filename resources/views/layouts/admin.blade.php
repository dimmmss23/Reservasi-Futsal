<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Futsal ID</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Rajdhani', sans-serif;
        }
        .font-display {
            font-family: 'Orbitron', sans-serif;
        }
        .neon-green {
            color: #00ff41;
        }
        .neon-glow {
            box-shadow: 0 0 10px #00ff41, 0 0 20px #00ff41, 0 0 30px #00ff41;
        }
        .neon-border {
            border: 2px solid #00ff41;
            box-shadow: 0 0 5px #00ff41, inset 0 0 5px #00ff41;
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: rgba(0, 255, 65, 0.1);
            border-left: 4px solid #00ff41;
            padding-left: 1.5rem;
        }
        .sidebar-link.active {
            background: rgba(0, 255, 65, 0.15);
            border-left: 4px solid #00ff41;
            padding-left: 1.5rem;
        }
    </style>
</head>
<body class="bg-black text-gray-300">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 border-r-2 border-gray-800 fixed h-full overflow-y-auto">
            <!-- Logo -->
            <div class="p-6 border-b-2 border-gray-800">
                <h1 class="text-3xl font-display font-bold neon-green">
                    <i class="fas fa-futbol mr-2"></i>FUTSAL ID
                </h1>
                <p class="text-sm text-gray-500 mt-1">Admin Panel</p>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b-2 border-gray-800 bg-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center neon-border">
                        <i class="fas fa-user-shield text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-green-400">Administrator</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-6"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.fields.index') }}" 
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('admin.fields.*') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt w-6"></i>
                    <span class="font-medium">Manage Fields</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    <span class="font-medium">Manage Users</span>
                </a>

                <a href="{{ route('admin.payments') }}" 
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-6"></i>
                    <span class="font-medium">Verify Payments</span>
                </a>

                <div class="border-t border-gray-800 my-4"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg mb-2 w-full text-left text-red-400 hover:bg-red-900/20">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Bar -->
            <header class="bg-gray-900 border-b-2 border-gray-800 px-8 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-display font-bold text-white">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500">@yield('page-subtitle', 'Manage your futsal business')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-400">{{ now()->format('l, d F Y') }}</p>
                            <p class="text-xs text-green-400" id="current-time"></p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if (session('success'))
            <div class="mx-8 mt-4 bg-green-900/50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                    <p class="text-green-300">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mx-8 mt-4 bg-red-900/50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                    <p class="text-red-300">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="border-t-2 border-gray-800 py-6 px-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} Futsal ID. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            document.getElementById('current-time').textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>

    @stack('scripts')
</body>
</html>
