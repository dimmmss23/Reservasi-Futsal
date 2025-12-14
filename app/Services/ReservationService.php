<?php

namespace App\Services;

use App\Models\Member;
use App\Models\User;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\PaymentDetail;
use App\Services\Payment\PaymentInterface;
use App\Exceptions\FieldUnavailableException;
use App\Exceptions\PaymentFailedException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class ReservationService
 * 
 * Service layer untuk menangani logika bisnis reservasi.
 * Menerapkan Dependency Injection untuk PaymentInterface (Strategy Pattern).
 */
class ReservationService
{
    /**
     * Payment gateway implementation
     * 
     * @var PaymentInterface
     */
    protected PaymentInterface $paymentGateway;

    /**
     * Constructor - Dependency Injection
     * 
     * @param PaymentInterface $paymentGateway
     */
    public function __construct(PaymentInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Membuat reservasi baru dengan pembayaran
     * 
     * @param User $member User object (bisa Member atau User biasa dengan role member)
     * @param int $fieldId
     * @param string $bookTime
     * @param int $duration
     * @return Reservation
     * @throws FieldUnavailableException
     * @throws PaymentFailedException
     */
    public function createReservation(
        User $member,
        int $fieldId,
        string $bookTime,
        int $duration
    ): Reservation {
        // Mulai database transaction untuk data consistency
        return DB::transaction(function () use ($member, $fieldId, $bookTime, $duration) {
            // Step 1: Validasi field existence
            $field = Field::findOrFail($fieldId);

            // Step 2: Cek ketersediaan lapangan
            if (!$field->isAvailableAt($bookTime, $duration)) {
                throw new FieldUnavailableException(
                    $field->name,
                    Carbon::parse($bookTime)->format('d M Y, H:i')
                );
            }

            // Step 3: Hitung total harga
            $totalPrice = $field->calculatePrice($duration);

            // Step 4: Buat reservasi (status pending)
            $reservation = Reservation::create([
                'member_id' => $member->id,
                'field_id' => $field->id,
                'book_time' => $bookTime,
                'duration' => $duration,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Step 5: Proses pembayaran via PaymentInterface (Polymorphism)
            $orderId = 'RES-' . $reservation->id . '-' . time();
            $paymentResult = $this->paymentGateway->pay($totalPrice, $orderId);

            // Step 6: Cek hasil pembayaran
            if ($paymentResult['status'] === 'failed') {
                // Rollback akan otomatis karena exception
                throw new PaymentFailedException(
                    $paymentResult['transaction_id'],
                    $this->paymentGateway->getMethodName(),
                    $totalPrice,
                    $paymentResult['message']
                );
            }

            // Step 7: Buat payment detail (Composition)
            $paymentDetail = PaymentDetail::create([
                'reservation_id' => $reservation->id,
                'transaction_id' => $paymentResult['transaction_id'],
                'amount' => $totalPrice,
                'payment_method' => $this->paymentGateway->getMethodName(),
                'payment_status' => $paymentResult['status'], // 'success', 'pending', atau 'verified'
            ]);

            // Step 8: Update status reservasi berdasarkan status pembayaran
            if ($paymentResult['status'] === 'success') {
                $reservation->update(['status' => 'confirmed']);
            }

            // Load relationships untuk response
            $reservation->load(['field', 'member', 'paymentDetail']);

            // Attach redirect_url jika ada (untuk payment gateway simulation)
            if (isset($paymentResult['redirect_url'])) {
                $reservation->redirect_url = $paymentResult['redirect_url'];
            }

            return $reservation;
        });
    }

    /**
     * Update metode pembayaran (untuk demonstrasi Polymorphism)
     * 
     * @param PaymentInterface $paymentGateway
     * @return void
     */
    public function setPaymentGateway(PaymentInterface $paymentGateway): void
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Mendapatkan nama metode pembayaran yang sedang aktif
     * 
     * @return string
     */
    public function getCurrentPaymentMethod(): string
    {
        return $this->paymentGateway->getMethodName();
    }

    /**
     * Cancel reservasi
     * 
     * @param int $reservationId
     * @param int $memberId
     * @return bool
     */
    public function cancelReservation(int $reservationId, int $memberId): bool
    {
        $reservation = Reservation::where('id', $reservationId)
            ->where('member_id', $memberId)
            ->firstOrFail();

        // Hanya bisa cancel jika status pending atau confirmed
        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            throw new \Exception('Reservasi tidak dapat dibatalkan.');
        }

        return DB::transaction(function () use ($reservation) {
            // Update payment detail status to failed
            $reservation->paymentDetail?->update(['payment_status' => 'failed']);
            
            // Cancel reservation (akan update status ke 'cancelled')
            return $reservation->cancel();
        });
    }

    /**
     * Mendapatkan daftar slot waktu yang tersedia untuk field tertentu
     * 
     * @param int $fieldId
     * @param string $date
     * @return array
     */
    public function getAvailableTimeSlots(int $fieldId, string $date): array
    {
        $field = Field::findOrFail($fieldId);
        $availableSlots = [];
        
        // Generate slot dari jam 08:00 sampai 22:00
        for ($hour = 8; $hour < 22; $hour++) {
            $timeSlot = Carbon::parse($date)->setTime($hour, 0);
            
            if ($field->isAvailableAt($timeSlot->toDateTimeString(), 1)) {
                $availableSlots[] = [
                    'time' => $timeSlot->format('H:i'),
                    'datetime' => $timeSlot->toDateTimeString(),
                ];
            }
        }
        
        return $availableSlots;
    }

    /**
     * Mendapatkan statistik reservasi member
     * 
     * @param int $memberId
     * @return array
     */
    public function getMemberReservationStats(int $memberId): array
    {
        $member = Member::findOrFail($memberId);
        
        return [
            'total' => $member->reservations()->count(),
            'completed' => $member->reservations()->where('status', 'completed')->count(),
            'upcoming' => $member->reservations()->upcoming()->count(),
            'points' => $member->points,
        ];
    }
}
