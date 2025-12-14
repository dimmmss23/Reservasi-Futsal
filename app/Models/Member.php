<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Member
 * 
 * Representasi user dengan role Member.
 * Member dapat melakukan booking lapangan futsal.
 * 
 * Inheritance: Member extends User (Single Table Inheritance)
 */
class Member extends User
{
    /**
     * Nama tabel database (Single Table Inheritance)
     */
    protected $table = 'users';

    /**
     * Boot method untuk set role secara otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            $member->role = 'member';
        });
    }

    /**
     * Scope untuk membatasi query hanya ke Member
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyMembers($query)
    {
        return $query->where('role', 'member');
    }

    /**
     * Override: Mendapatkan dashboard route name untuk Member
     * 
     * @return string
     */
    public function getDashboardUrl(): string
    {
        return 'member.dashboard';
    }

    /**
     * Relasi: Member memiliki banyak Reservation
     * 
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'member_id');
    }

    /**
     * Method: Melakukan registrasi member baru
     * 
     * @param array $data
     * @return Member
     */
    public static function register(array $data): Member
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'] ?? null,
            'points' => 0,
        ]);
    }

    /**
     * Method: Melakukan booking lapangan
     * Ini adalah wrapper method, actual logic ada di ReservationService
     * 
     * @param int $fieldId
     * @param string $bookTime
     * @param int $duration
     * @return Reservation
     */
    public function book(int $fieldId, string $bookTime, int $duration): Reservation
    {
        // Method ini akan memanggil ReservationService
        // Implementasi ada di controller dengan dependency injection
        return app(\App\Services\ReservationService::class)
            ->createReservation($this, $fieldId, $bookTime, $duration);
    }

    /**
     * Method: Menambah poin member
     * 
     * @param int $points
     * @return void
     */
    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
    }

    /**
     * Method: Mendapatkan total reservasi yang sudah completed
     * 
     * @return int
     */
    public function getTotalCompletedReservations(): int
    {
        return $this->reservations()->where('status', 'completed')->count();
    }
}
