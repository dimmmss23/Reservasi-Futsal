<?php

namespace App\Exceptions;

use Exception;

/**
 * Class PaymentFailedException
 * 
 * Exception yang dilempar ketika proses pembayaran gagal.
 */
class PaymentFailedException extends Exception
{
    protected $transactionId;
    protected $paymentMethod;
    protected $amount;

    /**
     * Constructor
     * 
     * @param string $transactionId ID transaksi yang gagal
     * @param string $paymentMethod Metode pembayaran yang digunakan
     * @param float $amount Jumlah pembayaran
     * @param string $message Pesan error custom
     */
    public function __construct(
        string $transactionId = '',
        string $paymentMethod = '',
        float $amount = 0,
        string $message = ''
    ) {
        $this->transactionId = $transactionId;
        $this->paymentMethod = $paymentMethod;
        $this->amount = $amount;
        
        if (empty($message)) {
            $message = "Pembayaran gagal untuk transaksi {$transactionId} menggunakan metode {$paymentMethod}. Jumlah: Rp " . number_format($amount, 0, ',', '.');
        }
        
        parent::__construct($message);
    }

    /**
     * Mendapatkan transaction ID
     * 
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Mendapatkan metode pembayaran
     * 
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * Mendapatkan jumlah pembayaran
     * 
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Render exception sebagai HTTP response
     * 
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response()->json([
            'error' => 'Payment Failed',
            'message' => $this->getMessage(),
            'transaction_id' => $this->transactionId,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->amount
        ], 402); // 402 Payment Required
    }
}
