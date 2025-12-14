@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Monitor your futsal business performance')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-lg p-6 border-2 border-green-700 shadow-lg hover:shadow-green-500/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-300 text-sm font-medium uppercase tracking-wide">Total Revenue</p>
                    <h3 class="text-3xl font-display font-bold text-white mt-2">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>
                    <p class="text-green-200 text-xs mt-1">from completed bookings</p>
                </div>
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center neon-border">
                    <i class="fas fa-money-bill-wave text-3xl neon-green"></i>
                </div>
            </div>
        </div>

        <!-- Pending Reservations -->
        <div class="bg-gradient-to-br from-yellow-900 to-yellow-800 rounded-lg p-6 border-2 border-yellow-700 shadow-lg hover:shadow-yellow-500/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-300 text-sm font-medium uppercase tracking-wide">Pending Payments</p>
                    <h3 class="text-3xl font-display font-bold text-white mt-2">
                        {{ $stats['pending'] }}
                    </h3>
                    <p class="text-yellow-200 text-xs mt-1">need verification</p>
                </div>
                <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center border-2 border-yellow-500">
                    <i class="fas fa-clock text-3xl text-yellow-400"></i>
                </div>
            </div>
        </div>

        <!-- Active Fields -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-lg p-6 border-2 border-blue-700 shadow-lg hover:shadow-blue-500/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-300 text-sm font-medium uppercase tracking-wide">Active Fields</p>
                    <h3 class="text-3xl font-display font-bold text-white mt-2">
                        {{ $activeFields }}
                    </h3>
                    <p class="text-blue-200 text-xs mt-1">available for booking</p>
                </div>
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center border-2 border-blue-500">
                    <i class="fas fa-futbol text-3xl text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Reservations -->
        <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-lg p-6 border-2 border-purple-700 shadow-lg hover:shadow-purple-500/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-300 text-sm font-medium uppercase tracking-wide">Total Bookings</p>
                    <h3 class="text-3xl font-display font-bold text-white mt-2">
                        {{ $stats['total'] }}
                    </h3>
                    <p class="text-purple-200 text-xs mt-1">all time reservations</p>
                </div>
                <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center border-2 border-purple-500">
                    <i class="fas fa-calendar-check text-3xl text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reservation Status Breakdown -->
        <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6">
            <h3 class="text-xl font-display font-bold text-white mb-4 flex items-center">
                <i class="fas fa-chart-pie mr-3 neon-green"></i>
                Reservation Status
            </h3>
            <div class="space-y-4">
                <!-- Confirmed -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-green-400 font-medium">Confirmed</span>
                        <span class="text-white font-bold">{{ $stats['confirmed'] }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full neon-glow" 
                             style="width: {{ $stats['total'] > 0 ? ($stats['confirmed'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>

                <!-- Pending -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-yellow-400 font-medium">Pending</span>
                        <span class="text-white font-bold">{{ $stats['pending'] }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" 
                             style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>

                <!-- Completed -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-blue-400 font-medium">Completed</span>
                        <span class="text-white font-bold">{{ $stats['completed'] }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" 
                             style="width: {{ $stats['total'] > 0 ? ($stats['completed'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>

                <!-- Cancelled -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-red-400 font-medium">Cancelled</span>
                        <span class="text-white font-bold">{{ $stats['cancelled'] }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-3">
                        <div class="bg-red-500 h-3 rounded-full" 
                             style="width: {{ $stats['total'] > 0 ? ($stats['cancelled'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6">
            <h3 class="text-xl font-display font-bold text-white mb-4 flex items-center">
                <i class="fas fa-bolt mr-3 neon-green"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.fields.create') }}" 
                   class="flex items-center justify-between p-4 bg-gray-800 hover:bg-gray-700 rounded-lg border-2 border-gray-700 hover:border-green-500 transition-all group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-plus-circle text-2xl text-green-400 group-hover:text-green-300"></i>
                        <div>
                            <p class="font-semibold text-white">Add New Field</p>
                            <p class="text-xs text-gray-400">Create a new futsal field</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-600 group-hover:text-green-400"></i>
                </a>

                <a href="{{ route('admin.payments') }}" 
                   class="flex items-center justify-between p-4 bg-gray-800 hover:bg-gray-700 rounded-lg border-2 border-gray-700 hover:border-yellow-500 transition-all group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-2xl text-yellow-400 group-hover:text-yellow-300"></i>
                        <div>
                            <p class="font-semibold text-white">Verify Payments</p>
                            <p class="text-xs text-gray-400">Review pending payments</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-600 group-hover:text-yellow-400"></i>
                </a>

                <a href="{{ route('admin.fields.index') }}" 
                   class="flex items-center justify-between p-4 bg-gray-800 hover:bg-gray-700 rounded-lg border-2 border-gray-700 hover:border-blue-500 transition-all group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-cog text-2xl text-blue-400 group-hover:text-blue-300"></i>
                        <div>
                            <p class="font-semibold text-white">Manage Fields</p>
                            <p class="text-xs text-gray-400">Edit field details</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-600 group-hover:text-blue-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Reservations (Sedang Berlangsung) -->
    <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-display font-bold text-white flex items-center">
                <i class="fas fa-clock mr-3 text-yellow-400"></i>
                Reservasi Sedang Berlangsung
            </h3>
            <span class="text-sm text-gray-400">Perlu ditandai selesai setelah waktu habis</span>
        </div>
        
        @if($activeReservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-800">
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">ID</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Member</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Lapangan</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Waktu Booking</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Durasi</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Selesai</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm w-48">Status Waktu</th>
                            <th class="text-center py-3 px-4 text-gray-400 font-semibold uppercase text-sm w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeReservations as $reservation)
                        @php
                            $bookTime = \Carbon\Carbon::parse($reservation->book_time);
                            $endTime = $bookTime->copy()->addHours($reservation->duration);
                            $now = \Carbon\Carbon::now();
                            $isExpired = $now->greaterThanOrEqualTo($endTime);
                            $isStarted = $now->greaterThanOrEqualTo($bookTime);
                            
                            // Hitung waktu tersisa dengan format yang lebih baik
                            if ($isExpired) {
                                $timeStatus = 'expired';
                                $diffHours = 0;
                                $diffMinutes = 0;
                            } elseif (!$isStarted) {
                                $timeStatus = 'upcoming';
                                // Hitung total menit sampai booking dimulai
                                $totalMinutes = $now->diffInMinutes($bookTime);
                                $diffHours = floor($totalMinutes / 60);
                                $diffMinutes = $totalMinutes % 60;
                            } else {
                                $timeStatus = 'ongoing';
                                // Hitung total menit sampai booking selesai
                                $totalMinutes = $now->diffInMinutes($endTime);
                                $diffHours = floor($totalMinutes / 60);
                                $diffMinutes = $totalMinutes % 60;
                            }
                        @endphp
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors">
                            <td class="py-4 px-4 text-white font-mono font-bold">RSV-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 px-4">
                                <div>
                                    <p class="text-white font-semibold">{{ $reservation->member->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $reservation->member->email }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-white">{{ $reservation->field->name }}</td>
                            <td class="py-4 px-4 text-gray-300">
                                {{ $bookTime->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-4 text-white font-semibold">{{ $reservation->duration }} jam</td>
                            <td class="py-4 px-4 text-gray-300 font-semibold">
                                {{ $endTime->format('H:i') }}
                            </td>
                            <td class="py-4 px-4 min-w-[180px]">
                                @if($timeStatus === 'expired')
                                    <span class="inline-block px-3 py-1 bg-red-900/50 text-red-400 rounded-full text-xs font-semibold border border-red-700 whitespace-nowrap">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Sudah Lewat
                                    </span>
                                @elseif($timeStatus === 'upcoming')
                                    <span class="inline-block px-3 py-1 bg-blue-900/50 text-blue-400 rounded-full text-xs font-semibold border border-blue-700 whitespace-nowrap">
                                        <i class="fas fa-clock mr-1"></i>
                                        @if($diffHours > 0)
                                            {{ $diffHours }}j {{ $diffMinutes }}m lagi
                                        @else
                                            {{ $diffMinutes }}m lagi
                                        @endif
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-yellow-900/50 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-700 whitespace-nowrap">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        @if($diffHours > 0)
                                            {{ $diffHours }}j {{ $diffMinutes }}m lagi
                                        @else
                                            {{ $diffMinutes }}m lagi
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('admin.reservations.complete', $reservation->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-900/50 hover:bg-green-800 text-green-400 rounded-lg font-semibold border border-green-700 transition-all text-sm"
                                            onclick="return confirm('Tandai reservasi ini sebagai selesai?')">
                                        <i class="fas fa-check mr-1"></i>
                                        Tandai Selesai
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-calendar-check text-6xl mb-4 text-gray-700"></i>
                <p class="text-lg">Tidak ada reservasi yang sedang berlangsung</p>
                <p class="text-sm mt-2">Semua reservasi sudah selesai atau belum ada yang dikonfirmasi</p>
            </div>
        @endif
    </div>

    <!-- Recent Reservations -->
    <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6">
        <h3 class="text-xl font-display font-bold text-white mb-4 flex items-center">
            <i class="fas fa-history mr-3 neon-green"></i>
            Recent Reservations
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-800">
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">ID</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Member</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Field</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Date & Time</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Total</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReservations as $reservation)
                    <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors">
                        <td class="py-3 px-4 text-white font-mono">RSV-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $reservation->member->name }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $reservation->field->name }}</td>
                        <td class="py-3 px-4 text-gray-300">
                            {{ \Carbon\Carbon::parse($reservation->book_time)->format('d M Y, H:i') }}
                        </td>
                        <td class="py-3 px-4 text-white font-semibold">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            @if($reservation->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-900/50 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-700">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($reservation->status === 'confirmed')
                                <span class="px-3 py-1 bg-green-900/50 text-green-400 rounded-full text-xs font-semibold border border-green-700">
                                    <i class="fas fa-check mr-1"></i>Confirmed
                                </span>
                            @elseif($reservation->status === 'completed')
                                <span class="px-3 py-1 bg-blue-900/50 text-blue-400 rounded-full text-xs font-semibold border border-blue-700">
                                    <i class="fas fa-check-double mr-1"></i>Completed
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-900/50 text-red-400 rounded-full text-xs font-semibold border border-red-700">
                                    <i class="fas fa-times mr-1"></i>Cancelled
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No reservations found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
