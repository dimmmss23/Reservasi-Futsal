@extends('layouts.admin')

@section('title', 'Verify Payments')
@section('page-title', 'Payment Verification')
@section('page-subtitle', 'Review and verify pending payments')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-yellow-900 to-yellow-800 rounded-lg p-6 border-2 border-yellow-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-300 text-sm font-medium uppercase">Pending</p>
                    <h3 class="text-3xl font-display font-bold text-white mt-2">{{ $pendingPayments->count() }}</h3>
                </div>
                <i class="fas fa-clock text-4xl text-yellow-400"></i>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-6">
        <h3 class="text-xl font-display font-bold text-white mb-4 flex items-center">
            <i class="fas fa-credit-card mr-3 neon-green"></i>
            Pending Payments
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-800">
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Payment ID</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Member</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Field</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Date</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Amount</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Method</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Bukti</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-semibold uppercase text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPayments as $payment)
                    <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors">
                        <td class="py-4 px-4">
                            <span class="font-mono text-white">{{ $payment->transaction_id }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <div>
                                <p class="text-white font-semibold">{{ $payment->reservation->member->name }}</p>
                                <p class="text-xs text-gray-400">{{ $payment->reservation->member->email }}</p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-gray-300">
                            {{ $payment->reservation->field->name }}
                        </td>
                        <td class="py-4 px-4 text-gray-300">
                            {{ \Carbon\Carbon::parse($payment->reservation->book_time)->format('d M Y, H:i') }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-white font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-1 bg-gray-800 text-gray-300 rounded text-xs">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            @if($payment->payment_proof)
                                <button onclick="showProof('{{ asset('storage/' . $payment->payment_proof) }}', '{{ pathinfo($payment->payment_proof, PATHINFO_EXTENSION) }}')" 
                                        class="px-3 py-1 bg-blue-900/50 hover:bg-blue-800 text-blue-400 rounded border border-blue-700 text-xs font-semibold transition-all">
                                    <i class="fas fa-image mr-1"></i>Lihat Bukti
                                </button>
                            @else
                                <span class="text-gray-500 text-xs italic">Belum upload</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-green-900/50 hover:bg-green-800 text-green-400 rounded border border-green-700 text-xs font-semibold transition-all"
                                            onclick="return confirm('Verify this payment?')">
                                        <i class="fas fa-check mr-1"></i>Verify
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-red-900/50 hover:bg-red-800 text-red-400 rounded border border-red-700 text-xs font-semibold transition-all"
                                            onclick="return confirm('Reject this payment?')">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <i class="fas fa-check-circle text-6xl text-green-700 mb-4"></i>
                            <p class="text-gray-500 text-lg">No pending payments</p>
                            <p class="text-gray-600 text-sm">All payments have been processed</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan bukti pembayaran -->
<div id="proofModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeProof()">
    <div class="bg-gray-900 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto border-2 border-green-500" onclick="event.stopPropagation()">
        <div class="p-4 border-b border-gray-800 flex items-center justify-between sticky top-0 bg-gray-900 z-10">
            <h3 class="text-xl font-bold text-white">
                <i class="fas fa-receipt mr-2 neon-green"></i>
                Bukti Pembayaran
            </h3>
            <button onclick="closeProof()" class="text-gray-400 hover:text-white text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="proofContent" class="p-6">
            <!-- Content will be inserted here -->
        </div>
        <div class="p-4 border-t border-gray-800 flex justify-end space-x-3 sticky bottom-0 bg-gray-900">
            <a id="downloadBtn" href="#" download class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded border border-blue-500 transition-all">
                <i class="fas fa-download mr-2"></i>Download
            </a>
            <button onclick="closeProof()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition-all">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<script>
function showProof(url, extension) {
    const modal = document.getElementById('proofModal');
    const content = document.getElementById('proofContent');
    const downloadBtn = document.getElementById('downloadBtn');
    
    downloadBtn.href = url;
    
    if (['jpg', 'jpeg', 'png'].includes(extension.toLowerCase())) {
        content.innerHTML = `
            <div class="flex justify-center">
                <img src="${url}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg border-2 border-gray-700">
            </div>
        `;
    } else if (extension.toLowerCase() === 'pdf') {
        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-file-pdf text-red-400 text-6xl mb-4"></i>
                <p class="text-white text-lg mb-2">File PDF</p>
                <p class="text-gray-400 text-sm">Klik tombol Download untuk melihat file PDF</p>
                <a href="${url}" target="_blank" class="mt-4 inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all">
                    <i class="fas fa-external-link-alt mr-2"></i>Buka di Tab Baru
                </a>
            </div>
        `;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProof() {
    const modal = document.getElementById('proofModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProof();
    }
});
</script>
@endsection
