<?php

namespace App\Services\Payment;

use Illuminate\Support\Str;

/**
 * Class ManualUploadMock
 * 
 * Implementasi mock untuk pembayaran dengan upload bukti transfer manual.
 * Status pembayaran akan pending sampai admin melakukan verifikasi.
 */
class ManualUploadMock implements PaymentInterface
{
    /**
     * Memproses pembayaran via Manual Upload (Mock)
     * 
     * @param float $amount
     * @param string $orderId
     * @return array
     */
    public function pay(float $amount, string $orderId): array
    {
        // Simulasi upload delay
        usleep(300000); // 0.3 detik
        
        // Generate transaction ID
        $transactionId = 'MUM-' . strtoupper(Str::random(12));
        
        // Manual upload selalu return pending (butuh verifikasi admin)
        return [
            'status' => 'pending',
            'transaction_id' => $transactionId,
            'message' => 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.',
            'amount' => $amount,
            'order_id' => $orderId,
            'note' => 'Verifikasi akan dilakukan dalam 1x24 jam.'
        ];
    }

    /**
     * Verifikasi status pembayaran (biasanya dipanggil oleh admin)
     * 
     * @param string $transactionId
     * @return array
     */
    public function verify(string $transactionId): array
    {
        // Mock verification - simulasi verifikasi manual oleh admin
        // Dalam implementasi nyata, ini akan mengecek database
        return [
            'status' => 'pending',
            'transaction_id' => $transactionId,
            'message' => 'Menunggu verifikasi manual dari admin.',
            'verified_at' => null
        ];
    }
    
    /**
     * Mendapatkan nama metode pembayaran
     * 
     * @return string
     */
    public function getMethodName(): string
    {
        return 'ManualUpload';
    }
}
