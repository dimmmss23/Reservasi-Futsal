<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Reservation
 * 
 * Representasi booking/reservasi lapangan futsal.
 */
class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'field_id',
        'book_time',
        'duration',
        'total_price',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'book_time' => 'datetime',
        'total_price' => 'decimal:2',
        'duration' => 'integer',
    ];

    /**
     * Relasi: Reservation dimiliki oleh Member
     * 
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Relasi: Reservation dimiliki oleh Field
     * 
     * @return BelongsTo
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Relasi: Reservation memiliki satu PaymentDetail (Composition)
     * 
     * @return HasOne
     */
    public function paymentDetail(): HasOne
    {
        return $this->hasOne(PaymentDetail::class);
    }

    /**
     * Scope: Filter berdasarkan status
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter reservasi hari ini
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('book_time', today());
    }

    /**
     * Scope: Filter reservasi mendatang
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('book_time', '>', now());
    }

    /**
     * Method: Confirm reservasi
     * 
     * @return bool
     */
    public function confirm(): bool
    {
        return $this->update(['status' => 'confirmed']);
    }

    /**
     * Method: Cancel reservasi
     * 
     * @return bool
     */
    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }

    /**
     * Method: Complete reservasi
     * 
     * @return bool
     */
    public function complete(): bool
    {
        $updated = $this->update(['status' => 'completed']);
        
        // Tambahkan poin ke member (misalnya 10 poin per reservasi)
        if ($updated) {
            $this->member->addPoints(10);
        }
        
        return $updated;
    }

    /**
     * Accessor: Format waktu booking
     * 
     * @return string
     */
    public function getFormattedBookTimeAttribute(): string
    {
        return $this->book_time->format('d M Y, H:i');
    }

    /**
     * Accessor: Format harga dengan Rupiah
     * 
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Accessor: Cek apakah reservasi sudah lewat
     * 
     * @return bool
     */
    public function getIsPastAttribute(): bool
    {
        return $this->book_time->isPast();
    }
}
