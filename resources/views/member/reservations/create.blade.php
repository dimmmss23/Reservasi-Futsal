@extends('layouts.app')

@section('title', 'Booking Lapangan - Futsal ID')

@section('content')

<section class="py-12 px-4">
    <div class="container mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-neon-green hover:underline mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
            <h1 class="font-orbitron text-4xl font-bold mb-2">
                BOOKING <span class="text-neon-green text-glow">LAPANGAN</span>
            </h1>
            <p class="text-gray-400">Isi form di bawah untuk melakukan reservasi</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Left: Field Info -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <!-- Field Image -->
                @if($field->image)
                    <div class="mb-6 -mx-6 -mt-6">
                        <img src="{{ asset('storage/' . $field->image) }}" 
                             alt="{{ $field->name }}" 
                             class="w-full h-48 object-cover rounded-t-xl">
                    </div>
                @endif
                
                <h3 class="text-2xl font-bold mb-4">{{ $field->name }}</h3>
                
                <div class="space-y-3 text-gray-300">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group w-6 text-neon-green mr-3"></i>
                        <span><strong>Jenis:</strong> {{ $field->type }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-money-bill-wave w-6 text-neon-green mr-3"></i>
                        <span><strong>Harga:</strong> {{ $field->formatted_price }}/jam</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-info-circle w-6 text-neon-green mr-3"></i>
                        <span><strong>Status:</strong> 
                            <span class="text-green-400 font-bold">{{ ucfirst($field->status) }}</span>
                        </span>
                    </div>
                </div>
                
                @if($field->description)
                    <div class="mt-6 p-4 bg-slate-900 rounded-lg">
                        <p class="text-sm text-gray-400">{{ $field->description }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Right: Booking Form -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-xl font-bold mb-6">Form Reservasi</h3>
                
                <form action="{{ route('reservations.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                    
                    <!-- Book Time -->
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">
                            <i class="fas fa-calendar-alt mr-2 text-neon-green"></i> Tanggal & Waktu
                        </label>
                        <input type="datetime-local" 
                               name="book_time" 
                               value="{{ old('book_time') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               required
                               class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:border-neon-green focus:outline-none transition">
                        @error('book_time')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">Pilih tanggal dan jam mulai booking</p>
                    </div>
                    
                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">
                            <i class="fas fa-clock mr-2 text-neon-green"></i> Durasi (Jam)
                        </label>
                        <select name="duration" 
                                id="duration"
                                required
                                class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:border-neon-green focus:outline-none transition">
                            <option value="1" {{ old('duration') == 1 ? 'selected' : '' }}>1 Jam</option>
                            <option value="2" {{ old('duration') == 2 ? 'selected' : '' }}>2 Jam</option>
                            <option value="3" {{ old('duration') == 3 ? 'selected' : '' }}>3 Jam</option>
                            <option value="4" {{ old('duration') == 4 ? 'selected' : '' }}>4 Jam</option>
                        </select>
                        @error('duration')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Availability Status Alert -->
                    <div id="availability-alert" class="hidden">
                        <!-- Will be filled by JavaScript -->
                    </div>
                    
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">
                            <i class="fas fa-credit-card mr-2 text-neon-green"></i> Metode Pembayaran
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 bg-slate-900 rounded-lg border border-slate-600 cursor-pointer hover:border-neon-green transition">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="BankTransfer" 
                                       {{ old('payment_method', 'BankTransfer') == 'BankTransfer' ? 'checked' : '' }}
                                       class="w-4 h-4 text-neon-green focus:ring-neon-green">
                                <div class="ml-3">
                                    <p class="font-bold">Bank Transfer (Mock)</p>
                                    <p class="text-xs text-gray-400">Verifikasi otomatis - Demo payment</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 bg-slate-900 rounded-lg border border-slate-600 cursor-pointer hover:border-neon-green transition">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="ManualUpload"
                                       {{ old('payment_method') == 'ManualUpload' ? 'checked' : '' }}
                                       class="w-4 h-4 text-neon-green focus:ring-neon-green">
                                <div class="ml-3">
                                    <p class="font-bold">Upload Bukti Manual</p>
                                    <p class="text-xs text-gray-400">Menunggu verifikasi admin - Demo</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror>
                    </div>
                    
                    <!-- Price Preview -->
                    <div class="p-4 bg-slate-900 rounded-lg border border-neon-green/30">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Total Harga:</span>
                            <span class="text-2xl font-bold text-neon-green" id="total-price">
                                {{ $field->formatted_price }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-neon-green text-slate-900 py-4 rounded-lg font-bold text-lg hover:glow-green-strong transition">
                        <i class="fas fa-check-circle mr-2"></i> Konfirmasi Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Calculate total price dynamically
    const pricePerHour = {{ $field->price_per_hour }};
    const durationSelect = document.getElementById('duration');
    const totalPriceElement = document.getElementById('total-price');
    const bookTimeInput = document.querySelector('input[name="book_time"]');
    const availabilityAlert = document.getElementById('availability-alert');
    const submitButton = document.querySelector('button[type="submit"]');
    
    // Update price when duration changes
    durationSelect.addEventListener('change', function() {
        const duration = parseInt(this.value);
        const totalPrice = pricePerHour * duration;
        totalPriceElement.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        checkAvailability();
    });
    
    // Check availability when time changes
    bookTimeInput.addEventListener('change', checkAvailability);
    
    function checkAvailability() {
        const bookTime = bookTimeInput.value;
        const duration = durationSelect.value;
        
        if (!bookTime || !duration) {
            availabilityAlert.classList.add('hidden');
            return;
        }
        
        // Show loading state
        availabilityAlert.innerHTML = `
            <div class="p-4 bg-blue-900/20 border border-blue-500 rounded-lg">
                <p class="text-blue-400 text-sm">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Mengecek ketersediaan slot...
                </p>
            </div>
        `;
        availabilityAlert.classList.remove('hidden');
        
        // AJAX check availability
        fetch(`/reservations/api/check-availability?field_id={{ $field->id }}&book_time=${encodeURIComponent(bookTime)}&duration=${duration}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    availabilityAlert.innerHTML = `
                        <div class="p-4 bg-green-900/20 border border-green-500 rounded-lg">
                            <p class="text-green-400 text-sm font-semibold">
                                <i class="fas fa-check-circle mr-2"></i>
                                Slot tersedia! Silakan lanjutkan booking.
                            </p>
                        </div>
                    `;
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    availabilityAlert.innerHTML = `
                        <div class="p-4 bg-red-900/20 border-2 border-red-500 rounded-lg">
                            <p class="text-red-400 text-sm font-bold mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Slot tidak tersedia!
                            </p>
                            <p class="text-red-300 text-xs">
                                ${data.message}
                            </p>
                        </div>
                    `;
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
                availabilityAlert.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error checking availability:', error);
                availabilityAlert.classList.add('hidden');
                submitButton.disabled = false;
            });
    }
</script>
@endpush

@endsection
