<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * Base class untuk semua tipe user dalam sistem.
 * Menerapkan Single Table Inheritance dengan discriminator column 'role'.
 * 
 * NOTE: Changed from abstract to concrete class untuk Laravel Auth compatibility.
 * Polymorphic behavior tetap dipertahankan melalui role-based routing.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'points',
        'staff_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    /**
     * Mendapatkan URL dashboard sesuai role (Polymorphic behavior)
     * 
     * Implementasi polymorphism tanpa abstract class:
     * - Admin akan redirect ke admin.dashboard
     * - Member akan redirect ke member.dashboard
     * 
     * @return string
     */
    public function getDashboardUrl(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'member' => 'member.dashboard',
            default => 'home',
        };
    }

    /**
     * Scope untuk filter user berdasarkan role
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Check apakah user adalah Member
     * 
     * @return bool
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check apakah user adalah Admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relationship: User (Member) has many Reservations
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'member_id');
    }
}


