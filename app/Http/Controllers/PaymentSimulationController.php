<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSimulationController extends Controller
{
    /**
     * Tampilkan halaman simulasi pembayaran
     */
    public function show($reservationId)
    {
        $reservation = Reservation::with(['field', 'member', 'paymentDetail'])
            ->findOrFail($reservationId);

        // Jika sudah paid, redirect ke dashboard
        if ($reservation->status === 'confirmed' || $reservation->status === 'completed') {
            return redirect()->route('member.dashboard')
                ->with('info', 'Pembayaran sudah berhasil sebelumnya.');
        }

        return view('payment.simulation', compact('reservation'));
    }

    /**
     * Proses pembayaran berhasil
     */
    public function success($reservationId)
    {
        DB::beginTransaction();
        
        try {
            $reservation = Reservation::with('paymentDetail')->findOrFail($reservationId);

            // Update status reservasi menjadi confirmed
            $reservation->update([
                'status' => 'confirmed'
            ]);

            // Update payment detail menjadi verified
            if ($reservation->paymentDetail) {
                $reservation->paymentDetail->update([
                    'payment_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => 'System Auto-Verify'
                ]);
            }

            DB::commit();

            return redirect()->route('member.dashboard')
                ->with('success', '🎉 Pembayaran Berhasil! Reservasi Anda telah dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('member.dashboard')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    /**
     * Proses pembayaran dibatalkan
     */
    public function failed($reservationId)
    {
        DB::beginTransaction();
        
        try {
            $reservation = Reservation::findOrFail($reservationId);

            // Update status menjadi cancelled
            $reservation->update([
                'status' => 'cancelled'
            ]);

            DB::commit();

            return redirect()->route('member.dashboard')
                ->with('warning', 'Pembayaran dibatalkan. Reservasi Anda telah dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('member.dashboard')
                ->with('error', 'Terjadi kesalahan saat membatalkan transaksi.');
        }
    }
}
