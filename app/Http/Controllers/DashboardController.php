<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Field;
use App\Models\Admin;

/**
 * Class DashboardController
 * 
 * Controller untuk menampilkan dashboard sesuai role user (OOP Polymorphism).
 * Admin akan melihat statistik dan management tools.
 * Member akan melihat history reservasi dan poin mereka.
 */
class DashboardController extends Controller
{
    /**
     * Display dashboard berdasarkan role user.
     * 
     * Implementasi Polymorphism:
     * - Menggunakan method getDashboardUrl() dari User abstract class
     * - Setiap subclass (Admin, Member) mengimplementasikan behavior berbeda
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // Polymorphic routing based on user role
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'member') {
            return $this->memberDashboard();
        }

        // Fallback jika role tidak dikenali
        abort(403, 'Unauthorized role');
    }

    /**
     * Admin Dashboard
     * 
     * Menampilkan:
     * - Total Revenue
     * - Statistics (Pending, Confirmed, Completed, Cancelled)
     * - Active Fields count
     * - Active Reservations (confirmed, sedang berlangsung)
     * - Recent Reservations
     * 
     * @return \Illuminate\View\View
     */
    private function adminDashboard()
    {
        // Cast user to Admin untuk mengakses Admin methods
        $admin = Admin::find(auth()->id());

        // Get statistics using Admin methods (OOP)
        $stats = $admin->getReservationStatistics();
        $totalRevenue = $admin->getTotalRevenue();

        // Get active fields
        $activeFields = Field::where('status', 'available')->count();

        // Get active reservations (confirmed, belum completed)
        $activeReservations = Reservation::with(['member', 'field'])
            ->where('status', 'confirmed')
            ->orderBy('book_time', 'asc')
            ->get();

        // Get recent reservations (latest 10)
        $recentReservations = Reservation::with(['member', 'field'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
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
     * Member Dashboard
     * 
     * Menampilkan:
     * - User Points
     * - My Reservations (All history)
     * - Upcoming Reservations count
     * 
     * @return \Illuminate\View\View
     */
    private function memberDashboard()
    {
        $member = auth()->user();

        // Get all reservations for this member
        $reservations = Reservation::with(['field', 'paymentDetail'])
            ->where('member_id', $member->id)
            ->orderBy('book_time', 'desc')
            ->get();

        // Count upcoming reservations (confirmed or pending, and future date)
        $upcomingCount = Reservation::where('member_id', $member->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('book_time', '>', now())
            ->count();

        return view('member.dashboard', compact(
            'reservations',
            'upcomingCount'
        ));
    }

    /**
     * Redirect ke dashboard sesuai role
     * 
     * Helper method untuk redirect dari route 'dashboard'
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        $user = auth()->user();

        // Gunakan polymorphic method getDashboardUrl() dari User
        return redirect()->route($user->getDashboardUrl());
    }
}
