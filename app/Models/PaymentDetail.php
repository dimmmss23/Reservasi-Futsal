<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PaymentDetail
 * 
 * Representasi detail pembayaran untuk sebuah reservasi.
 * Composition relationship: PaymentDetail tidak bisa exist tanpa Reservation.
 */
class PaymentDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reservation_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'verified_at',
        'verified_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi: PaymentDetail dimiliki oleh Reservation (Composition)
     * 
     * @return BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Scope: Filter berdasarkan status pembayaran
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope: Filter pembayaran pending (butuh verifikasi)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope: Filter berdasarkan metode pembayaran
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Method: Mark pembayaran sebagai sukses
     * 
     * @return bool
     */
    public function markAsSuccess(): bool
    {
        $updated = $this->update(['payment_status' => 'verified']);
        
        // Update status reservasi menjadi confirmed
        if ($updated) {
            $this->reservation->confirm();
        }
        
        return $updated;
    }

    /**
     * Method: Mark pembayaran sebagai gagal
     * 
     * @return bool
     */
    public function markAsFailed(): bool
    {
        $updated = $this->update(['payment_status' => 'rejected']);
        
        // Update status reservasi menjadi cancelled
        if ($updated) {
            $this->reservation->cancel();
        }
        
        return $updated;
    }

    /**
     * Method: Cek apakah pembayaran sudah berhasil
     * 
     * @return bool
     */
    public function isSuccess(): bool
    {
        return in_array($this->payment_status, ['verified', 'success']);
    }

    /**
     * Method: Cek apakah pembayaran masih pending
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Accessor: Format jumlah dengan Rupiah
     * 
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Accessor: Get status badge class untuk UI
     * 
     * @return string
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->payment_status) {
            'verified', 'success' => 'bg-green-500/20 text-green-400 border-green-500',
            'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500',
            'rejected', 'failed' => 'bg-red-500/20 text-red-400 border-red-500',
            default => 'bg-gray-500/20 text-gray-400 border-gray-500',
        };
    }
}
