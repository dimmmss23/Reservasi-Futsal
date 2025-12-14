<?php

namespace App\Services\Payment;

/**
 * Interface PaymentInterface
 * 
 * Kontrak untuk implementasi berbagai metode pembayaran.
 * Menerapkan Strategy Pattern untuk Polymorphism.
 */
interface PaymentInterface
{
    /**
     * Memproses pembayaran
     * 
     * @param float $amount Jumlah yang harus dibayar
     * @param string $orderId ID pesanan/reservasi
     * @return array Status pembayaran dengan struktur ['status' => 'success/failed', 'transaction_id' => '...', 'message' => '...']
     */
    public function pay(float $amount, string $orderId): array;

    /**
     * Verifikasi status pembayaran
     * 
     * @param string $transactionId ID transaksi pembayaran
     * @return array Status verifikasi
     */
    public function verify(string $transactionId): array;
    
    /**
     * Mendapatkan nama metode pembayaran
     * 
     * @return string
     */
    public function getMethodName(): string;
}
