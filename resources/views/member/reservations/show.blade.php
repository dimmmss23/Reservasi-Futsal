@extends('layouts.app')

@section('title', 'Detail Reservasi - Futsal ID')

@section('content')

<section class="py-12 px-4">
    <div class="container mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('reservations.index') }}" class="text-neon-green hover:underline mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Reservasi
            </a>
            <h1 class="font-orbitron text-4xl font-bold mb-2">
                DETAIL <span class="text-neon-green text-glow">RESERVASI</span>
            </h1>
        </div>
        
        <!-- Main Card -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
            <!-- Header with Status -->
            <div class="p-6 bg-slate-900 border-b border-slate-700 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Booking ID</p>
                    <p class="text-xl font-bold font-mono">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <span class="px-6 py-3 rounded-full text-sm font-bold
                    @if($reservation->status === 'confirmed') bg-green-500/20 text-green-400 border border-green-500
                    @elseif($reservation->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500
                    @elseif($reservation->status === 'completed') bg-blue-500/20 text-blue-400 border border-blue-500
                    @else bg-red-500/20 text-red-400 border border-red-500
                    @endif">
                    {{ ucfirst($reservation->status) }}
                </span>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <!-- Field Info -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-400 mb-4">INFORMASI LAPANGAN</h3>
                    <div class="bg-slate-900 rounded-lg p-6">
                        <h4 class="text-2xl font-bold mb-4">{{ $reservation->field->name }}</h4>
                        <div class="grid md:grid-cols-2 gap-4 text-gray-300">
                            <div class="flex items-center">
                                <i class="fas fa-layer-group w-6 text-neon-green mr-3"></i>
                                <span>Jenis: <strong>{{ $reservation->field->type }}</strong></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave w-6 text-neon-green mr-3"></i>
                                <span>Harga: <strong>{{ $reservation->field->formatted_price }}/jam</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-400 mb-4">DETAIL BOOKING</h3>
                    <div class="bg-slate-900 rounded-lg p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Tanggal & Waktu</p>
                                <p class="text-lg font-bold">
                                    <i class="fas fa-calendar-alt text-neon-green mr-2"></i>
                                    {{ $reservation->formatted_book_time }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Durasi</p>
                                <p class="text-lg font-bold">
                                    <i class="fas fa-clock text-neon-green mr-2"></i>
                                    {{ $reservation->duration }} Jam
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Member</p>
                                <p class="text-lg font-bold">
                                    <i class="fas fa-user text-neon-green mr-2"></i>
                                    {{ $reservation->member->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Total Harga</p>
                                <p class="text-2xl font-bold text-neon-green">
                                    {{ $reservation->formatted_price }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Details -->
                @if($reservation->paymentDetail)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-400 mb-4">DETAIL PEMBAYARAN</h3>
                        <div class="bg-slate-900 rounded-lg p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Transaction ID</p>
                                    <p class="text-sm font-mono font-bold">{{ $reservation->paymentDetail->transaction_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Metode Pembayaran</p>
                                    <p class="font-bold">{{ $reservation->paymentDetail->payment_method }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Status Pembayaran</p>
                                    <span class="inline-block px-4 py-2 rounded-full text-xs font-bold 
                                        @if($reservation->paymentDetail->payment_status === 'verified' || $reservation->paymentDetail->payment_status === 'success') 
                                            bg-green-500/20 text-green-400 border border-green-500
                                        @elseif($reservation->paymentDetail->payment_status === 'pending')
                                            bg-yellow-500/20 text-yellow-400 border border-yellow-500
                                        @else
                                            bg-red-500/20 text-red-400 border border-red-500
                                        @endif">
                                        {{ ucfirst($reservation->paymentDetail->payment_status) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Jumlah</p>
                                    <p class="text-xl font-bold text-neon-green">{{ $reservation->paymentDetail->formatted_amount }}</p>
                                </div>
                            </div>
                            
                            <!-- Bukti Pembayaran yang Sudah Diupload -->
                            @if($reservation->paymentDetail->payment_proof)
                                <div class="mt-6 p-6 bg-green-900/20 border-2 border-green-500 rounded-lg">
                                    <h4 class="text-green-400 font-bold mb-4 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Bukti Pembayaran Telah Diupload
                                    </h4>
                                    
                                    <div class="bg-gray-900 p-4 rounded-lg">
                                        @php
                                            $extension = pathinfo($reservation->paymentDetail->payment_proof, PATHINFO_EXTENSION);
                                        @endphp
                                        
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                            <!-- Preview Image -->
                                            <img src="{{ asset('storage/' . $reservation->paymentDetail->payment_proof) }}" 
                                                 alt="Bukti Pembayaran" 
                                                 class="max-w-full h-auto rounded-lg border-2 border-gray-700 mb-4 max-h-96 object-contain mx-auto">
                                        @elseif(strtolower($extension) === 'pdf')
                                            <!-- PDF Icon -->
                                            <div class="text-center py-8">
                                                <i class="fas fa-file-pdf text-red-400 text-6xl mb-4"></i>
                                                <p class="text-gray-300 mb-4">File PDF telah diupload</p>
                                            </div>
                                        @endif
                                        
                                        <!-- Download Button -->
                                        <a href="{{ asset('storage/' . $reservation->paymentDetail->payment_proof) }}" 
                                           target="_blank"
                                           download
                                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-semibold transition-all border-2 border-green-500">
                                            <i class="fas fa-download mr-2"></i>
                                            Download Bukti Pembayaran
                                        </a>
                                    </div>
                                    
                                    @if($reservation->paymentDetail->payment_status === 'pending')
                                        <p class="text-sm text-gray-400 mt-4 text-center">
                                            <i class="fas fa-hourglass-half mr-2"></i>
                                            Bukti pembayaran sedang diverifikasi oleh admin. Mohon tunggu konfirmasi.
                                        </p>
                                    @elseif(in_array($reservation->paymentDetail->payment_status, ['verified', 'success']))
                                        <p class="text-sm text-green-400 mt-4 text-center">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Bukti pembayaran telah diverifikasi oleh admin. Pembayaran berhasil!
                                        </p>
                                    @elseif(in_array($reservation->paymentDetail->payment_status, ['rejected', 'failed']))
                                        <p class="text-sm text-red-400 mt-4 text-center">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Bukti pembayaran ditolak oleh admin. Silakan hubungi customer service.
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            @if($reservation->paymentDetail->payment_status === 'pending')
                                <div class="mt-6 p-6 bg-yellow-900/20 border-2 border-yellow-500 rounded-lg">
                                    <div class="flex items-start mb-4">
                                        <i class="fas fa-info-circle text-yellow-400 text-xl mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="text-yellow-400 font-bold mb-2">Pembayaran Menunggu Verifikasi</h4>
                                            <p class="text-gray-300 text-sm mb-4">
                                                @if(!$reservation->paymentDetail->payment_proof)
                                                    Upload bukti pembayaran Anda untuk mempercepat proses verifikasi. 
                                                    Pembayaran akan diverifikasi dalam 1x24 jam.
                                                @else
                                                    Bukti pembayaran Anda sudah kami terima. Sedang dalam proses verifikasi admin (1x24 jam).
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Upload Bukti Pembayaran -->
                                    @if(!$reservation->paymentDetail->payment_proof)
                                        <form action="{{ route('reservations.upload-proof', $reservation->id) }}" 
                                              method="POST" 
                                              enctype="multipart/form-data"
                                              class="bg-gray-900 p-4 rounded-lg">
                                            @csrf
                                            
                                            <label class="block mb-3">
                                                <span class="text-sm font-semibold text-gray-300 mb-2 block">
                                                    <i class="fas fa-upload mr-2 text-green-400"></i>
                                                    Upload Bukti Pembayaran (JPG, PNG, PDF - Max 2MB)
                                                </span>
                                                <input type="file" 
                                                       name="payment_proof" 
                                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                                       required
                                                       class="block w-full text-sm text-gray-300
                                                              file:mr-4 file:py-2 file:px-4
                                                              file:rounded-lg file:border-0
                                                              file:text-sm file:font-semibold
                                                              file:bg-green-600 file:text-white
                                                              hover:file:bg-green-700
                                                              file:cursor-pointer cursor-pointer
                                                              border-2 border-gray-700 rounded-lg
                                                              focus:border-green-500 transition-colors">
                                            </label>

                                            <button type="submit" 
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition-all border-2 border-green-500 shadow-lg hover:shadow-green-500/50">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Submit Bukti Pembayaran
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Informasi Transfer -->
                                    <div class="mt-4 p-4 bg-gray-900 rounded-lg border border-gray-700">
                                        <h5 class="text-sm font-bold text-gray-400 mb-3">
                                            <i class="fas fa-university mr-2"></i>
                                            Informasi Rekening Transfer:
                                        </h5>
                                        <div class="space-y-2 text-sm text-gray-300">
                                            <p><strong class="text-green-400">Bank BCA:</strong> 1234567890 a/n Futsal ID</p>
                                            <p><strong class="text-green-400">Bank Mandiri:</strong> 9876543210 a/n Futsal ID</p>
                                            <p><strong class="text-green-400">Total:</strong> <span class="text-xl font-bold text-green-400">{{ $reservation->paymentDetail->formatted_amount }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Actions -->
                <div class="flex gap-4">
                    @if(in_array($reservation->status, ['pending', 'confirmed']))
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?')"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500/20 hover:bg-red-500/30 border border-red-500 text-red-400 py-3 rounded-lg font-bold transition">
                                <i class="fas fa-times-circle mr-2"></i> Batalkan Reservasi
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('reservations.index') }}" 
                       class="flex-1 bg-slate-700 hover:bg-slate-600 text-center py-3 rounded-lg font-bold transition">
                        <i class="fas fa-list mr-2"></i> Lihat Semua Reservasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
