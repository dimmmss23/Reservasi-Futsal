@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="min-h-screen bg-black py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-white mb-2">
                Welcome back, <span class="neon-green">{{ auth()->user()->name }}</span>! 
                <i class="fas fa-user-circle ml-2"></i>
            </h1>
            <p class="text-gray-400">Track your reservations and manage your bookings</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Points Card -->
            <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-lg p-6 border-2 border-green-700 shadow-lg neon-glow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-300 text-sm font-medium uppercase tracking-wide">My Points</p>
                        <h3 class="text-4xl font-display font-bold text-white mt-2">
                            {{ auth()->user()->points ?? 0 }}
                        </h3>
                        <p class="text-green-200 text-xs mt-1">loyalty points earned</p>
                    </div>
                    <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center neon-border">
                        <i class="fas fa-star text-3xl neon-green"></i>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-lg p-6 border-2 border-blue-700 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-300 text-sm font-medium uppercase tracking-wide">Total Bookings</p>
                        <h3 class="text-4xl font-display font-bold text-white mt-2">
                            {{ $reservations->count() }}
                        </h3>
                        <p class="text-blue-200 text-xs mt-1">all time reservations</p>
                    </div>
                    <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center border-2 border-blue-500">
                        <i class="fas fa-calendar-check text-3xl text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings -->
            <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-lg p-6 border-2 border-purple-700 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-300 text-sm font-medium uppercase tracking-wide">Upcoming</p>
                        <h3 class="text-4xl font-display font-bold text-white mt-2">
                            {{ $upcomingCount }}
                        </h3>
                        <p class="text-purple-200 text-xs mt-1">future reservations</p>
                    </div>
                    <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center border-2 border-purple-500">
                        <i class="fas fa-clock text-3xl text-purple-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6 mb-8">
            <h2 class="text-2xl font-display font-bold text-white mb-4 flex items-center">
                <i class="fas fa-bolt mr-3 neon-green"></i>
                Quick Actions
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('home') }}" 
                   class="flex items-center justify-between p-4 bg-gray-800 hover:bg-gray-700 rounded-lg border-2 border-gray-700 hover:border-green-500 transition-all group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-plus-circle text-2xl text-green-400 group-hover:text-green-300"></i>
                        <div>
                            <p class="font-semibold text-white">New Booking</p>
                            <p class="text-xs text-gray-400">Reserve a field now</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-600 group-hover:text-green-400"></i>
                </a>

                <a href="{{ route('member.dashboard') }}" 
                   class="flex items-center justify-between p-4 bg-gray-800 hover:bg-gray-700 rounded-lg border-2 border-gray-700 hover:border-blue-500 transition-all group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-sync-alt text-2xl text-blue-400 group-hover:text-blue-300"></i>
                        <div>
                            <p class="font-semibold text-white">Refresh</p>
                            <p class="text-xs text-gray-400">Update reservation list</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-600 group-hover:text-blue-400"></i>
                </a>
            </div>
        </div>

        <!-- My Reservations -->
        <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-white flex items-center">
                    <i class="fas fa-list-ul mr-3 neon-green"></i>
                    My Reservations
                </h2>
                <div class="flex space-x-2">
                    <button onclick="filterStatus('all')" 
                            class="filter-btn px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg text-sm font-semibold transition-all border border-gray-700 active">
                        All
                    </button>
                    <button onclick="filterStatus('pending')" 
                            class="filter-btn px-4 py-2 bg-gray-800 hover:bg-gray-700 text-yellow-400 rounded-lg text-sm font-semibold transition-all border border-gray-700">
                        Pending
                    </button>
                    <button onclick="filterStatus('confirmed')" 
                            class="filter-btn px-4 py-2 bg-gray-800 hover:bg-gray-700 text-green-400 rounded-lg text-sm font-semibold transition-all border border-gray-700">
                        Confirmed
                    </button>
                    <button onclick="filterStatus('completed')" 
                            class="filter-btn px-4 py-2 bg-gray-800 hover:bg-gray-700 text-blue-400 rounded-lg text-sm font-semibold transition-all border border-gray-700">
                        Completed
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-800">
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Booking ID</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Field</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Date & Time</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Duration</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Total Price</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Payment</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Status</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                        <tr class="reservation-row border-b border-gray-800 hover:bg-gray-800 transition-colors" data-status="{{ $reservation->status }}">
                            <td class="py-4 px-4">
                                <span class="font-mono text-white font-semibold">
                                    RSV-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <p class="text-white font-semibold">{{ $reservation->field->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $reservation->field->type }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-calendar mr-1 text-green-400"></i>
                                {{ \Carbon\Carbon::parse($reservation->book_time)->format('d M Y') }}
                                <br>
                                <i class="fas fa-clock mr-1 text-green-400"></i>
                                {{ \Carbon\Carbon::parse($reservation->book_time)->format('H:i') }} WIB
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <span class="bg-gray-800 px-3 py-1 rounded-full text-sm whitespace-nowrap">
                                    {{ $reservation->duration }} hour{{ $reservation->duration > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-white font-bold">
                                    Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($reservation->paymentDetail)
                                    <div class="text-xs">
                                        <p class="text-gray-400">{{ ucfirst($reservation->paymentDetail->payment_method) }}</p>
                                        @if($reservation->status === 'cancelled' || $reservation->paymentDetail->payment_status === 'failed')
                                            <span class="text-red-400">
                                                <i class="fas fa-times-circle"></i> Failed
                                            </span>
                                        @elseif(in_array($reservation->paymentDetail->payment_status, ['verified', 'success']))
                                            <span class="text-green-400">
                                                <i class="fas fa-check-circle"></i> Paid
                                            </span>
                                        @elseif($reservation->paymentDetail->payment_status === 'pending')
                                            <span class="text-yellow-400">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="text-red-400">
                                                <i class="fas fa-times-circle"></i> Failed
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">No payment</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($reservation->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-900/50 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-700 inline-flex items-center">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($reservation->status === 'confirmed')
                                    <span class="px-3 py-1 bg-green-900/50 text-green-400 rounded-full text-xs font-semibold border border-green-700 inline-flex items-center">
                                        <i class="fas fa-check mr-1"></i>Confirmed
                                    </span>
                                @elseif($reservation->status === 'completed')
                                    <span class="px-3 py-1 bg-blue-900/50 text-blue-400 rounded-full text-xs font-semibold border border-blue-700 inline-flex items-center">
                                        <i class="fas fa-check-double mr-1"></i>Completed
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-900/50 text-red-400 rounded-full text-xs font-semibold border border-red-700 inline-flex items-center">
                                        <i class="fas fa-times mr-1"></i>Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('reservations.show', $reservation->id) }}" 
                                       class="px-3 py-1 bg-blue-900/50 hover:bg-blue-800 text-blue-400 rounded border border-blue-700 text-xs font-semibold transition-all"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                                    <form method="POST" action="{{ route('reservations.destroy', $reservation->id) }}" 
                                          onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1 bg-red-900/50 hover:bg-red-800 text-red-400 rounded border border-red-700 text-xs font-semibold transition-all"
                                                title="Cancel Booking">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <i class="fas fa-inbox text-6xl text-gray-700 mb-4"></i>
                                <p class="text-gray-500 text-lg">No reservations found</p>
                                <a href="{{ route('home') }}" 
                                   class="mt-4 inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all neon-border">
                                    <i class="fas fa-plus mr-2"></i>Make Your First Booking
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function filterStatus(status) {
        const rows = document.querySelectorAll('.reservation-row');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update active button
        buttons.forEach(btn => btn.classList.remove('active', 'neon-border'));
        event.target.classList.add('active', 'neon-border');
        
        // Filter rows
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<style>
    .filter-btn.active {
        background: rgba(0, 255, 65, 0.2);
        border-color: #00ff41 !important;
        color: #00ff41 !important;
    }
</style>
@endsection
