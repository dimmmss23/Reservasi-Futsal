<?php

namespace App\Models;

/**
 * Class Admin
 * 
 * Representasi user dengan role Admin.
 * Admin dapat mengelola lapangan, verifikasi pembayaran, dan melihat statistik.
 * 
 * Inheritance: Admin extends User (Single Table Inheritance)
 */
class Admin extends User
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

        static::creating(function ($admin) {
            $admin->role = 'admin';
        });
    }

    /**
     * Scope untuk membatasi query hanya ke Admin
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Override: Mendapatkan dashboard route name untuk Admin
     * 
     * @return string
     */
    public function getDashboardUrl(): string
    {
        return 'admin.dashboard';
    }

    /**
     * Method: Mengelola lapangan (Create)
     * 
     * @param array $data
     * @return Field
     */
    public function manageField(array $data): Field
    {
        return Field::create($data);
    }

    /**
     * Method: Update status lapangan
     * 
     * @param Field $field
     * @param string $status
     * @return bool
     */
    public function updateFieldStatus(Field $field, string $status): bool
    {
        return $field->update(['status' => $status]);
    }

    /**
     * Method: Verifikasi pembayaran manual
     * 
     * @param PaymentDetail $payment
     * @return bool
     */
    public function verifyPayment(PaymentDetail $payment): bool
    {
        $updated = $payment->update(['status' => 'success']);
        
        if ($updated) {
            // Update status reservasi menjadi confirmed
            $payment->reservation->update(['status' => 'confirmed']);
        }
        
        return $updated;
    }

    /**
     * Method: Menolak pembayaran manual
     * 
     * @param PaymentDetail $payment
     * @return bool
     */
    public function rejectPayment(PaymentDetail $payment): bool
    {
        $updated = $payment->update(['status' => 'failed']);
        
        if ($updated) {
            // Update status reservasi menjadi cancelled
            $payment->reservation->update(['status' => 'cancelled']);
        }
        
        return $updated;
    }

    /**
     * Method: Mendapatkan statistik reservasi
     * 
     * @return array
     */
    public function getReservationStatistics(): array
    {
        return [
            'total' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Method: Mendapatkan total revenue
     * 
     * @return float
     */
    public function getTotalRevenue(): float
    {
        return Reservation::where('status', 'completed')
            ->sum('total_price');
    }
}
