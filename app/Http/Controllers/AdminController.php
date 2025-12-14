<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Member;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * AdminController
 * 
 * Menangani fungsi-fungsi admin seperti mengelola lapangan dan verifikasi pembayaran.
 */
class AdminController extends Controller
{
    /**
     * Dashboard admin
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $admin = Auth::user();
        
        // Statistics
        $stats = [
            'total' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];
        
        // Total Revenue
        $totalRevenue = Reservation::where('status', 'completed')->sum('total_price');
        
        // Active Fields
        $activeFields = Field::where('status', 'available')->count();
        
        // Active Reservations (confirmed, belum completed)
        $activeReservations = Reservation::with(['member', 'field'])
            ->where('status', 'confirmed')
            ->orderBy('book_time', 'asc')
            ->get();

        // Recent Reservations
        $recentReservations = Reservation::with(['member', 'field', 'paymentDetail'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'totalRevenue',
            'activeFields',
            'activeReservations',
            'recentReservations'
        ));
    }

    /**
     * Menampilkan daftar lapangan
     * 
     * @return \Illuminate\View\View
     */
    public function fields()
    {
        $fields = Field::orderBy('id', 'asc')->get();
        
        return view('admin.fields.index', compact('fields'));
    }

    /**
     * Menampilkan form tambah lapangan
     * 
     * @return \Illuminate\View\View
     */
    public function createField()
    {
        return view('admin.fields.create');
    }

    /**
     * Menyimpan lapangan baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeField(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Sintetis,Vinyl',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'field_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('field_images', $filename, 'public');
            $validated['image'] = $path;
        }

        // Create field directly
        $field = Field::create($validated);

        return redirect()
            ->route('admin.fields.index')
            ->with('success', "Lapangan '{$field->name}' berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit lapangan
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editField(int $id)
    {
        $field = Field::findOrFail($id);
        
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update lapangan
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateField(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Sintetis,Vinyl',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ]);

        $field = Field::findOrFail($id);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($field->image) {
                \Storage::disk('public')->delete($field->image);
            }
            
            $image = $request->file('image');
            $filename = 'field_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('field_images', $filename, 'public');
            $validated['image'] = $path;
        }
        
        $field->update($validated);

        return redirect()
            ->route('admin.fields.index')
            ->with('success', "Lapangan '{$field->name}' berhasil diupdate.");
    }

    /**
     * Menampilkan daftar pembayaran pending
     * 
     * @return \Illuminate\View\View
     */
    public function payments()
    {
        $pendingPayments = PaymentDetail::pending()
            ->with(['reservation.member', 'reservation.field'])
            ->latest()
            ->get();

        return view('admin.payments.index', compact('pendingPayments'));
    }

    /**
     * Verifikasi pembayaran
     * 
     * @param int $paymentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPayment(int $paymentId)
    {
        $payment = PaymentDetail::findOrFail($paymentId);

        // Update payment status to verified
        $payment->update([
            'payment_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->user()->name
        ]);
        
        // Update reservation status to confirmed
        $payment->reservation->update(['status' => 'confirmed']);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Menolak pembayaran
     * 
     * @param int $paymentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectPayment(int $paymentId)
    {
        $payment = PaymentDetail::findOrFail($paymentId);

        // Update payment status to rejected
        $payment->update([
            'payment_status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->user()->name
        ]);
        
        // Update reservation status to cancelled
        $payment->reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    /**
     * Tandai reservasi sebagai selesai (Manual Admin Control)
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeReservation(int $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            // Validasi hanya bisa complete jika status confirmed
            if ($reservation->status !== 'confirmed') {
                return redirect()->back()->with('error', 'Reservasi tidak dalam status confirmed.');
            }
            
            // Update status menjadi completed
            $reservation->update(['status' => 'completed']);
            
            return redirect()->back()->with('success', 'Reservasi berhasil ditandai selesai. Lapangan kembali tersedia untuk booking.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menandai reservasi selesai: ' . $e->getMessage());
        }
    }

    // ========================================================================
    // USER MANAGEMENT (CRUD)
    // ========================================================================

    /**
     * Menampilkan daftar semua user (Admin + Member)
     * 
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        // Count by role
        $totalUsers = $users->count();
        $totalAdmins = $users->where('role', 'admin')->count();
        $totalMembers = $users->where('role', 'member')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmins', 'totalMembers'));
    }

    /**
     * Menampilkan form tambah user baru
     * 
     * @return \Illuminate\View\View
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,member',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        
        // Set default points untuk member
        if ($validated['role'] === 'member') {
            $validated['points'] = 0;
        }

        // Create user
        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit user
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editUser(int $id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,member',
        ]);

        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$user->name}' berhasil diupdate.");
    }

    /**
     * Hapus user
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(int $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting yourself
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $userName = $user->name;
            
            // Delete user (cascade akan handle reservations)
            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', "User '{$userName}' berhasil dihapus.");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
