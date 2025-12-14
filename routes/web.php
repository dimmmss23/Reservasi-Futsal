<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentSimulationController;

/*
|--------------------------------------------------------------------------
| Web Routes - Futsal ID System
|--------------------------------------------------------------------------
|
| Struktur routing untuk sistem reservasi futsal dengan OOP architecture.
|
*/

// ============================================================================
// PUBLIC ROUTES (Guest)
// ============================================================================

// Landing Page & Field Listing
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/fields/{id}', [HomeController::class, 'show'])->name('fields.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================================
// PAYMENT SIMULATION ROUTES (For Demo)
// ============================================================================

Route::middleware('auth')->prefix('payment')->name('payment.simulation.')->group(function () {
    Route::get('/simulation/{reservationId}', [PaymentSimulationController::class, 'show'])->name('show');
    Route::post('/simulation/{reservationId}/success', [PaymentSimulationController::class, 'success'])->name('success');
    Route::post('/simulation/{reservationId}/failed', [PaymentSimulationController::class, 'failed'])->name('failed');
});

// ============================================================================
// MEMBER ROUTES (Authenticated)
// ============================================================================

Route::middleware('auth')->group(function () {
    
    // Universal Dashboard Route (Polymorphic - routes based on user role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Member Dashboard
    Route::get('/member/dashboard', [DashboardController::class, 'index'])->name('member.dashboard');
    
    // Reservation Management (Full CRUD)
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create/{fieldId}', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{id}', [ReservationController::class, 'show'])->name('show');
        Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('destroy');
        
        // Upload bukti pembayaran
        Route::get('/{id}/upload-proof', [ReservationController::class, 'showUploadProofPage'])->name('upload-proof-page');
        Route::post('/{id}/upload-proof', [ReservationController::class, 'uploadProof'])->name('upload-proof');
        
        // API endpoint untuk check availability
        Route::get('/api/check-availability', [ReservationController::class, 'checkAvailability'])
            ->name('check-availability');
        
        // API endpoint untuk available slots
        Route::get('/api/available-slots', [ReservationController::class, 'availableSlots'])
            ->name('available-slots');
    });
});

// ============================================================================
// ADMIN ROUTES (Admin Only)
// ============================================================================

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard (uses DashboardController for consistency)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Field Management (uses AdminController)
    Route::get('/fields', [AdminController::class, 'fields'])->name('fields.index');
    Route::get('/fields/create', [AdminController::class, 'createField'])->name('fields.create');
    Route::post('/fields', [AdminController::class, 'storeField'])->name('fields.store');
    Route::get('/fields/{id}/edit', [AdminController::class, 'editField'])->name('fields.edit');
    Route::put('/fields/{id}', [AdminController::class, 'updateField'])->name('fields.update');
    
    // User Management (CRUD)
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Payment Verification
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::post('/payments/{id}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');
    
    // Reservation Management (Complete Reservation)
    Route::patch('/reservations/{id}/complete', [AdminController::class, 'completeReservation'])->name('reservations.complete');
});
