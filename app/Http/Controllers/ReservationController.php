<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\ReservationService;
use App\Services\Payment\BankTransferMock;
use App\Services\Payment\ManualUploadMock;
use App\Exceptions\FieldUnavailableException;
use App\Exceptions\PaymentFailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReservationController
 * 
 * Menangani CRUD reservasi dengan Dependency Injection ke ReservationService.
 * Demonstrasi Polymorphism dengan switching payment method.
 */
class ReservationController extends Controller
{
    /**
     * Reservation Service
     * 
     * @var ReservationService
     */
    protected ReservationService $reservationService;

    /**
     * Constructor - Dependency Injection
     * 
     * @param ReservationService $reservationService
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Menampilkan daftar reservasi member
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $member = Auth::user();
        $reservations = $member->reservations()
            ->with(['field', 'paymentDetail'])
            ->latest()
            ->get();

        // Count upcoming reservations (confirmed or pending, and future date)
        $upcomingCount = $reservations
            ->whereIn('status', ['pending', 'confirmed'])
            ->filter(fn($r) => $r->book_time > now())
            ->count();

        return view('member.dashboard', compact('reservations', 'upcomingCount'));
    }

    /**
     * Menampilkan form booking baru
     * 
     * @param int $fieldId
     * @return \Illuminate\View\View
     */
    public function create(int $fieldId)
    {
        $field = \App\Models\Field::findOrFail($fieldId);
        
        // Get available time slots for today
        $availableSlots = $this->reservationService->getAvailableTimeSlots(
            $fieldId,
            now()->toDateString()
        );

        return view('member.reservations.create', compact('field', 'availableSlots'));
    }

    /**
     * Menyimpan reservasi baru (dengan Exception Handling)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'book_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:8',
            'payment_method' => 'required|in:BankTransfer,ManualUpload',
        ]);

        try {
            // Demonstrasi Polymorphism: Pilih payment gateway berdasarkan input user
            $paymentGateway = match($validated['payment_method']) {
                'BankTransfer' => new BankTransferMock(),
                'ManualUpload' => new ManualUploadMock(),
            };

            // Set payment gateway secara dinamis (Strategy Pattern)
            $this->reservationService->setPaymentGateway($paymentGateway);

            // Buat reservasi
            $member = Auth::user();
            $reservation = $this->reservationService->createReservation(
                $member,
                $validated['field_id'],
                $validated['book_time'],
                $validated['duration']
            );

            // Jika ada redirect_url (untuk payment gateway simulation), redirect ke sana
            if (isset($reservation->redirect_url)) {
                return redirect($reservation->redirect_url);
            }

            // Jika payment method Manual Upload, redirect ke halaman upload proof
            if ($validated['payment_method'] === 'ManualUpload') {
                return redirect()
                    ->route('reservations.upload-proof-page', $reservation->id)
                    ->with('info', 'Silakan upload bukti pembayaran untuk melanjutkan.');
            }

            // Success message berdasarkan status pembayaran (untuk BankTransfer atau lainnya)
            $message = $reservation->status === 'confirmed'
                ? 'Reservasi berhasil dibuat dan pembayaran terverifikasi!'
                : 'Reservasi berhasil dibuat. Menunggu verifikasi pembayaran.';

            return redirect()
                ->route('reservations.show', $reservation->id)
                ->with('success', $message);

        } catch (FieldUnavailableException $e) {
            // Handle field unavailable exception
            return back()
                ->withInput()
                ->with('error', $e->getMessage())
                ->with('error_type', 'field_unavailable');

        } catch (PaymentFailedException $e) {
            // Handle payment failed exception
            return back()
                ->withInput()
                ->with('error', $e->getMessage())
                ->with('error_type', 'payment_failed');

        } catch (\Exception $e) {
            // Handle other exceptions
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail reservasi
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        $reservation = Auth::user()
            ->reservations()
            ->with(['field', 'paymentDetail'])
            ->findOrFail($id);

        return view('member.reservations.show', compact('reservation'));
    }

    /**
     * Membatalkan reservasi
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        try {
            $member = Auth::user();
            $this->reservationService->cancelReservation($id, $member->id);

            return redirect()
                ->route('reservations.index')
                ->with('success', 'Reservasi berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman upload bukti pembayaran
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showUploadProofPage(int $id)
    {
        $reservation = Auth::user()
            ->reservations()
            ->with(['field', 'paymentDetail'])
            ->findOrFail($id);

        // Cek apakah payment method adalah Manual Upload
        if ($reservation->paymentDetail->payment_method !== 'ManualUpload') {
            return redirect()->route('reservations.show', $id);
        }

        // Cek apakah sudah upload bukti
        if ($reservation->paymentDetail->payment_proof) {
            return redirect()
                ->route('reservations.show', $id)
                ->with('info', 'Bukti pembayaran sudah diupload sebelumnya.');
        }

        return view('member.reservations.upload-proof', compact('reservation'));
    }

    /**
     * Upload bukti pembayaran
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadProof(Request $request, int $id)
    {
        // Validasi file upload
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // max 2MB
        ]);

        try {
            // Cari reservasi milik user yang login
            $reservation = Auth::user()
                ->reservations()
                ->with('paymentDetail')
                ->findOrFail($id);

            // Cek apakah status masih pending
            if ($reservation->status !== 'pending') {
                return back()->with('error', 'Bukti pembayaran hanya bisa diupload untuk reservasi dengan status pending.');
            }

            // Upload file
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                
                // Generate unique filename
                $filename = 'proof_' . $reservation->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store file ke storage/app/public/payment_proofs
                $path = $file->storeAs('payment_proofs', $filename, 'public');

                // Update payment detail
                if ($reservation->paymentDetail) {
                    $reservation->paymentDetail->update([
                        'payment_proof' => $path,
                    ]);
                }

                return redirect()
                    ->route('reservations.show', $id)
                    ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
            }

            return back()->with('error', 'Gagal mengupload file.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API: Mendapatkan slot waktu yang tersedia
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableSlots(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
        ]);

        $slots = $this->reservationService->getAvailableTimeSlots(
            $validated['field_id'],
            $validated['date']
        );

        return response()->json([
            'success' => true,
            'slots' => $slots,
        ]);
    }
    
    /**
     * API: Check availability untuk waktu dan durasi tertentu
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'book_time' => 'required|date',
            'duration' => 'required|integer|min:1|max:8',
        ]);
        
        $field = \App\Models\Field::findOrFail($validated['field_id']);
        $isAvailable = $field->isAvailableAt(
            $validated['book_time'],
            $validated['duration']
        );
        
        if ($isAvailable) {
            return response()->json([
                'available' => true,
                'message' => 'Slot tersedia untuk booking'
            ]);
        } else {
            return response()->json([
                'available' => false,
                'message' => 'Lapangan sudah dibooking pada waktu tersebut. Silakan pilih waktu lain.'
            ]);
        }
    }
}
