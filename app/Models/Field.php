<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Field
 * 
 * Representasi lapangan futsal dalam sistem.
 */
class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'status',
        'description',
        'image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price_per_hour' => 'decimal:2',
    ];

    /**
     * Relasi: Field memiliki banyak Reservation
     * 
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Scope: Filter lapangan yang tersedia
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope: Filter berdasarkan tipe lapangan
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Method: Cek apakah lapangan tersedia pada waktu tertentu
     * 
     * @param string $bookTime
     * @param int $duration
     * @return bool
     */
    public function isAvailableAt(string $bookTime, int $duration = 1): bool
    {
        // Cek apakah lapangan dalam status available
        if ($this->status !== 'available') {
            return false;
        }

        // Cek apakah ada booking yang konflik (hanya pending dan confirmed yang block slot)
        $requestedStart = \Carbon\Carbon::parse($bookTime);
        $requestedEnd = $requestedStart->copy()->addHours($duration);

        $conflictingReservations = $this->reservations()
            ->whereIn('status', ['pending', 'confirmed']) // Hanya yang aktif
            ->get()
            ->filter(function ($reservation) use ($requestedStart, $requestedEnd) {
                $existingStart = \Carbon\Carbon::parse($reservation->book_time);
                $existingEnd = $existingStart->copy()->addHours($reservation->duration);

                // Cek overlap: booking baru overlap dengan booking yang sudah ada
                return $requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart);
            });

        return $conflictingReservations->isEmpty();
    }

    /**
     * Method: Hitung harga untuk durasi tertentu
     * 
     * @param int $hours
     * @return float
     */
    public function calculatePrice(int $hours): float
    {
        return $this->price_per_hour * $hours;
    }

    /**
     * Method: Update status lapangan
     * 
     * @param string $status
     * @return bool
     */
    public function updateStatus(string $status): bool
    {
        return $this->update(['status' => $status]);
    }

    /**
     * Accessor: Format harga dengan Rupiah
     * 
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_hour, 0, ',', '.');
    }
}
