@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - Futsal ID')

@section('content')

<section class="py-12 px-4">
    <div class="container mx-auto max-w-3xl">
        
        <!-- Alert Info -->
        @if(session('info'))
        <div class="mb-6 bg-blue-500/20 border-2 border-blue-500 text-blue-400 px-6 py-4 rounded-lg flex items-start animate-fade-in">
            <i class="fas fa-info-circle text-2xl mr-4 mt-1"></i>
            <div>
                <p class="font-semibold mb-1">Informasi</p>
                <p class="text-sm">{{ session('info') }}</p>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500/20 rounded-full mb-4 border-2 border-green-500">
                <i class="fas fa-file-upload text-3xl text-green-400"></i>
            </div>
            <h1 class="font-orbitron text-4xl font-bold mb-2">
                UPLOAD <span class="text-neon-green text-glow">BUKTI PEMBAYARAN</span>
            </h1>
            <p class="text-gray-400 text-lg">
                Upload bukti transfer Anda untuk melanjutkan proses reservasi
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
            
            <!-- Booking Summary -->
            <div class="p-6 bg-slate-900 border-b border-slate-700">
                <h3 class="text-sm font-bold text-gray-400 mb-4">RINGKASAN BOOKING</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Booking ID</span>
                        <span class="font-mono font-bold">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Lapangan</span>
                        <span class="font-bold">{{ $reservation->field->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Tanggal & Waktu</span>
                        <span class="font-bold">{{ $reservation->formatted_book_time }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Durasi</span>
                        <span class="font-bold">{{ $reservation->duration }} Jam</span>
                    </div>
                    <div class="h-px bg-slate-700 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-lg">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-neon-green">{{ $reservation->formatted_price }}</span>
                    </div>
                </div>
            </div>

            <!-- Transfer Instructions -->
            <div class="p-6 bg-yellow-900/10 border-b border-slate-700">
                <div class="flex items-start mb-4">
                    <i class="fas fa-university text-yellow-400 text-2xl mr-4 mt-1"></i>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-yellow-400 mb-3">Informasi Rekening Transfer</h3>
                        <div class="space-y-3 text-gray-300">
                            <div class="bg-slate-900 p-4 rounded-lg border border-slate-700">
                                <p class="text-sm text-gray-400 mb-1">Bank BCA</p>
                                <p class="text-lg font-bold text-white">1234567890</p>
                                <p class="text-sm text-gray-400">a/n Futsal ID</p>
                            </div>
                            <div class="bg-slate-900 p-4 rounded-lg border border-slate-700">
                                <p class="text-sm text-gray-400 mb-1">Bank Mandiri</p>
                                <p class="text-lg font-bold text-white">9876543210</p>
                                <p class="text-sm text-gray-400">a/n Futsal ID</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-slate-900 p-4 rounded-lg border border-yellow-500/30 mt-4">
                    <h4 class="text-sm font-bold text-yellow-400 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Penting!
                    </h4>
                    <ul class="text-sm text-gray-300 space-y-1 list-disc list-inside">
                        <li>Transfer dengan nominal <strong class="text-neon-green">{{ $reservation->formatted_price }}</strong></li>
                        <li>Upload bukti transfer dalam format JPG, PNG, atau PDF (Max 2MB)</li>
                        <li>Verifikasi akan dilakukan dalam 1x24 jam</li>
                        <li>Anda akan mendapat notifikasi setelah pembayaran diverifikasi</li>
                    </ul>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="p-6">
                <form action="{{ route('reservations.upload-proof', $reservation->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      id="uploadForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block mb-3">
                            <span class="text-sm font-semibold text-gray-300 mb-2 block">
                                <i class="fas fa-image mr-2 text-green-400"></i>
                                Pilih Bukti Pembayaran *
                            </span>
                            <div class="relative">
                                <input type="file" 
                                       name="payment_proof" 
                                       id="payment_proof"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       required
                                       class="block w-full text-sm text-gray-300
                                              file:mr-4 file:py-3 file:px-6
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-green-600 file:text-white
                                              hover:file:bg-green-700
                                              file:cursor-pointer cursor-pointer
                                              border-2 border-slate-700 rounded-lg
                                              focus:border-green-500 transition-colors
                                              bg-slate-900 py-3 px-4">
                                <p class="text-xs text-gray-400 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: JPG, PNG, PDF • Max: 2MB
                                </p>
                            </div>
                            @error('payment_proof')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </label>

                        <!-- Preview Area -->
                        <div id="preview-container" class="hidden mt-4">
                            <p class="text-sm text-gray-400 mb-2">Preview:</p>
                            <div class="bg-slate-900 p-4 rounded-lg border-2 border-green-500">
                                <img id="image-preview" src="" alt="Preview" class="max-w-full h-auto rounded-lg max-h-64 object-contain mx-auto">
                                <div id="file-info" class="text-center mt-3 text-sm text-gray-400"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" 
                                id="submitBtn"
                                disabled
                                class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-slate-700 disabled:cursor-not-allowed text-white py-4 rounded-lg font-bold transition-all border-2 border-green-500 disabled:border-slate-600 shadow-lg hover:shadow-green-500/50 disabled:shadow-none">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span id="submitText">Upload Bukti Pembayaran</span>
                        </button>
                        
                        <a href="{{ route('reservations.show', $reservation->id) }}" 
                           class="bg-slate-700 hover:bg-slate-600 text-white py-4 px-6 rounded-lg font-bold transition text-center">
                            <i class="fas fa-times mr-2"></i>
                            Nanti Saja
                        </a>
                    </div>

                    <p class="text-xs text-gray-400 text-center mt-4">
                        <i class="fas fa-lock mr-1"></i>
                        Data Anda aman dan terenkripsi
                    </p>
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-slate-800 rounded-lg border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-gray-400 mb-4">
                <i class="fas fa-question-circle text-blue-400 mr-2"></i>
                Butuh Bantuan?
            </h3>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-300">
                <div class="flex items-start">
                    <i class="fas fa-phone text-green-400 mr-3 mt-1"></i>
                    <div>
                        <p class="font-bold mb-1">Telepon</p>
                        <p>+62 812-3456-7890</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope text-green-400 mr-3 mt-1"></i>
                    <div>
                        <p class="font-bold mb-1">Email</p>
                        <p>support@futsalid.com</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fab fa-whatsapp text-green-400 mr-3 mt-1"></i>
                    <div>
                        <p class="font-bold mb-1">WhatsApp</p>
                        <p>+62 812-3456-7890</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-clock text-green-400 mr-3 mt-1"></i>
                    <div>
                        <p class="font-bold mb-1">Jam Operasional</p>
                        <p>08:00 - 22:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Preview Image before upload
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const submitBtn = document.getElementById('submitBtn');
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');
        const fileInfo = document.getElementById('file-info');
        
        if (file) {
            // Enable submit button
            submitBtn.disabled = false;
            
            // Show preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                imagePreview.classList.add('hidden');
                previewContainer.classList.remove('hidden');
            }
            
            // Show file info
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.innerHTML = `
                <i class="fas fa-file mr-2"></i>
                <strong>${file.name}</strong> (${fileSize} MB)
            `;
            
            // Validate file size
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                this.value = '';
                submitBtn.disabled = true;
                previewContainer.classList.add('hidden');
            }
        } else {
            submitBtn.disabled = true;
            previewContainer.classList.add('hidden');
        }
    });
    
    // Loading state on submit
    document.getElementById('uploadForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        
        submitBtn.disabled = true;
        submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
    });
</script>
@endpush

@endsection
