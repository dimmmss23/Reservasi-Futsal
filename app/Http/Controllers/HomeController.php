<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * HomeController
 * 
 * Menangani halaman landing page dan daftar lapangan
 */
class HomeController extends Controller
{
    /**
     * Menampilkan landing page
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $fields = Field::all(); // Ambil semua lapangan
        
        // Tambahkan informasi booking hari ini untuk setiap lapangan
        foreach ($fields as $field) {
            $todayBookings = $field->reservations()
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereDate('book_time', Carbon::today())
                ->orderBy('book_time')
                ->get();
            
            $field->todayBookings = $todayBookings;
            $field->hasBookingsToday = $todayBookings->count() > 0;
        }
        
        return view('pages.home', compact('fields'));
    }

    /**
     * Menampilkan detail lapangan
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        $field = Field::findOrFail($id);
        
        return view('fields.show', compact('field'));
    }
}
