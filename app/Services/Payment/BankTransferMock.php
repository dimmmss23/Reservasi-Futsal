<?php

namespace App\Services\Payment;

use Illuminate\Support\Str;

/**
 * Class BankTransferMock
 * 
 * Implementasi mock untuk pembayaran via Bank Transfer.
 * Simulasi verifikasi otomatis sukses (untuk keperluan demo).
 */
class BankTransferMock implements PaymentInterface
{
    /**
     * Memproses pembayaran via Bank Transfer (Mock)
     * Mengarahkan ke halaman simulasi pembayaran
     * 
     * @param float $amount
     * @param string $orderId
     * @return array
     */
    public function pay(float $amount, string $orderId): array
    {
        // Generate transaction ID
        $transactionId = 'BTM-' . strtoupper(Str::random(12));
        
        // Extract reservation ID from orderId (format: RES-{id}-{timestamp})
        preg_match('/RES-(\d+)-/', $orderId, $matches);
        $reservationId = $matches[1] ?? null;
        
        // Return redirect URL ke halaman simulasi pembayaran
        return [
            'status' => 'pending',
            'transaction_id' => $transactionId,
            'message' => 'Silakan lanjutkan ke halaman pembayaran.',
            'amount' => $amount,
            'order_id' => $orderId,
            'redirect_url' => $reservationId ? route('payment.simulation.show', $reservationId) : null
        ];
    }

    /**
     * Verifikasi status pembayaran
     * 
     * @param string $transactionId
     * @return array
     */
    public function verify(string $transactionId): array
    {
        // Mock verification - selalu return success untuk demo
        return [
            'status' => 'success',
            'transaction_id' => $transactionId,
            'verified_at' => now()->toDateTimeString()
        ];
    }
    
    /**
     * Mendapatkan nama metode pembayaran
     * 
     * @return string
     */
    public function getMethodName(): string
    {
        return 'BankTransfer';
    }
}
