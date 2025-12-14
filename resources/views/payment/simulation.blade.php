<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment - Futsal ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }
        
        .glass-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .secure-badge {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
            }
            50% {
                box-shadow: 0 0 30px rgba(34, 197, 94, 0.8);
            }
        }
        
        .loading-dots span {
            animation: loading 1.4s infinite;
        }
        
        .loading-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .loading-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes loading {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.5;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .btn-pay {
            transition: all 0.3s ease;
        }
        
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(34, 197, 94, 0.4);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-2xl" x-data="{ loading: true, showPayment: false }" x-init="setTimeout(() => { loading = false; showPayment = true; }, 2000)">
        
        <!-- Loading State -->
        <div x-show="loading" x-transition class="glass-card rounded-2xl p-12 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
            </div>
            <h2 class="text-white text-2xl font-bold mb-3">Menghubungkan ke Bank...</h2>
            <p class="text-gray-400 mb-6">Mohon tunggu sebentar</p>
            <div class="loading-dots flex justify-center space-x-2">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
            </div>
        </div>

        <!-- Payment Card -->
        <div x-show="showPayment" x-transition.duration.500ms class="glass-card rounded-2xl overflow-hidden shadow-2xl">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-shield-halved text-white text-3xl"></i>
                            <div>
                                <h1 class="text-white text-2xl font-bold">Futsal ID</h1>
                                <p class="text-green-100 text-sm">Secure Payment Gateway</p>
                            </div>
                        </div>
                    </div>
                    <div class="secure-badge bg-green-500/20 border-2 border-green-400 rounded-full px-4 py-2">
                        <i class="fas fa-lock text-green-400 mr-2"></i>
                        <span class="text-green-100 text-sm font-semibold">256-bit SSL</span>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="p-8">
                <div class="mb-8">
                    <h2 class="text-white text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-file-invoice text-green-500 mr-3"></i>
                        Detail Pesanan
                    </h2>
                    
                    <div class="bg-slate-800/50 rounded-xl p-6 border border-slate-700">
                        <!-- Order ID -->
                        <div class="flex justify-between mb-4 pb-4 border-b border-slate-700">
                            <span class="text-gray-400">Order ID</span>
                            <span class="text-white font-mono font-bold">RSV-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <!-- Field Name -->
                        <div class="flex justify-between mb-4 pb-4 border-b border-slate-700">
                            <span class="text-gray-400">Lapangan</span>
                            <span class="text-white font-semibold">{{ $reservation->field->name }}</span>
                        </div>

                        <!-- Booking Time -->
                        <div class="flex justify-between mb-4 pb-4 border-b border-slate-700">
                            <span class="text-gray-400">Waktu Main</span>
                            <span class="text-white font-semibold">
                                {{ \Carbon\Carbon::parse($reservation->book_time)->format('d M Y, H:i') }} WIB
                            </span>
                        </div>

                        <!-- Duration -->
                        <div class="flex justify-between mb-4 pb-4 border-b border-slate-700">
                            <span class="text-gray-400">Durasi</span>
                            <span class="text-white font-semibold">{{ $reservation->duration }} Jam</span>
                        </div>

                        <!-- Price per Hour -->
                        <div class="flex justify-between mb-4 pb-4 border-b border-slate-700">
                            <span class="text-gray-400">Harga per Jam</span>
                            <span class="text-white">Rp {{ number_format($reservation->field->price_per_hour, 0, ',', '.') }}</span>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-300 text-lg font-semibold">Total Pembayaran</span>
                            <div class="text-right">
                                <div class="text-green-400 text-3xl font-bold">
                                    Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                                </div>
                                <div class="text-gray-500 text-xs mt-1">Sudah termasuk biaya administrasi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="bg-blue-900/20 border border-blue-700/50 rounded-lg p-4 mb-8">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                        <div>
                            <p class="text-blue-300 text-sm font-semibold mb-1">Transaksi Aman</p>
                            <p class="text-blue-200 text-xs">
                                Pembayaran Anda dilindungi dengan enkripsi SSL 256-bit. 
                                Data kartu tidak akan disimpan di server kami.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <!-- Pay Button -->
                    <form action="{{ route('payment.simulation.success', $reservation->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="btn-pay w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-bold py-5 rounded-xl text-lg shadow-lg">
                            <i class="fas fa-check-circle mr-3"></i>
                            KONFIRMASI PEMBAYARAN
                        </button>
                    </form>

                    <!-- Cancel Button -->
                    <form action="{{ route('payment.simulation.failed', $reservation->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-slate-800 hover:bg-slate-700 text-gray-400 hover:text-white font-semibold py-3 rounded-xl border border-slate-700 transition-all"
                                onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
                            <i class="fas fa-times-circle mr-2"></i>
                            Batalkan Transaksi
                        </button>
                    </form>
                </div>

                <!-- Payment Methods (Visual Only) -->
                <div class="mt-8 pt-6 border-t border-slate-700">
                    <p class="text-gray-400 text-xs text-center mb-4">Metode Pembayaran yang Tersedia</p>
                    <div class="flex justify-center space-x-4 opacity-50">
                        <div class="bg-white rounded px-3 py-2">
                            <span class="text-blue-600 font-bold text-sm">VISA</span>
                        </div>
                        <div class="bg-white rounded px-3 py-2">
                            <span class="text-orange-600 font-bold text-sm">Mastercard</span>
                        </div>
                        <div class="bg-gradient-to-r from-blue-600 to-blue-400 rounded px-3 py-2">
                            <span class="text-white font-bold text-sm">BCA</span>
                        </div>
                        <div class="bg-red-600 rounded px-3 py-2">
                            <span class="text-white font-bold text-sm">Mandiri</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-900 px-8 py-4 text-center">
                <p class="text-gray-500 text-xs">
                    <i class="fas fa-lock mr-1"></i>
                    Powered by Futsal ID Secure Payment System
                </p>
            </div>
        </div>

        <!-- Back to Dashboard Link -->
        <div class="text-center mt-6" x-show="showPayment">
            <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-white text-sm transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>
