<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public API
Route::post('/api/xendit/webhook', [App\Http\Controllers\WebhookController::class, 'handle']);

Route::get('/api/package/{id}', function ($id) {    
    $package = \App\MakeupPackage::findOrFail($id);
    return response()->json([
        'id' => $package->id,
        'name' => $package->name,
        'description' => $package->description,
        'price' => $package->price,
        'duration' => $package->duration,
        'includes' => $package->includes,
        'images' => $package->images ?? [],
    ]);
})->name('api.package.show');

// API untuk mendapatkan tanggal yang sudah di-booking dengan jumlah booking per tanggal
Route::get('/api/booked-dates', function () {
    $bookings = \App\Booking::whereIn('status', ['pending', 'confirmed', 'on_progress'])
        ->where('booking_date', '>=', now()->format('Y-m-d'))
        ->select('booking_date')
        ->get()
        ->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        })
        ->map(function ($dateBookings) {
            return $dateBookings->count();
        });

    return response()->json([
        'booked_dates' => $bookings
    ]);
})->name('api.booked-dates');

// API untuk mendapatkan waktu booking pada tanggal tertentu
Route::get('/api/booked-times/{date}', function ($date) {
    $bookings = \App\Booking::whereIn('status', ['pending', 'confirmed', 'on_progress'])
        ->where('booking_date', $date)
        ->select('booking_time')
        ->get()
        ->map(function ($booking) {
            // booking_time is stored as time (HH:MM:SS), extract just HH:MM
            $time = $booking->booking_time;
            if (is_string($time)) {
                // If it's a string like "08:00:00", extract "08:00"
                return substr($time, 0, 5);
            }
            // If it's already formatted or Carbon instance
            return \Carbon\Carbon::parse($time)->format('H:i');
        })
        ->toArray();

    return response()->json([
        'booked_times' => $bookings
    ]);
})->name('api.booked-times');

// API untuk mendapatkan add-ons dari paket
Route::get('/api/package/{id}/addons', function ($id) {
    $package = \App\MakeupPackage::with(['addOns' => function($query) {
        $query->orderBy('order')->orderBy('name');
    }])->findOrFail($id);
    return response()->json([
        'add_ons' => $package->addOns
    ]);
})->name('api.package.addons');

// API untuk mendapatkan detail booking
Route::get('/api/booking/{id}', function ($id) {
    $booking = \App\Booking::with(['customer', 'package', 'payment'])
        ->findOrFail($id);

    // Check authorization
    if (\Illuminate\Support\Facades\Auth::user()->isCustomer() && $booking->customer_id !== \Illuminate\Support\Facades\Auth::id()) {
        abort(403);
    }

    // Format booking data untuk JSON response
    $bookingData = [
        'id' => $booking->id,
        'booking_code' => $booking->booking_code,
        'booking_date' => $booking->booking_date->format('Y-m-d'),
        'booking_time' => $booking->booking_time,
        'event_location' => $booking->event_location,
        'event_type' => $booking->event_type,
        'notes' => $booking->notes,
        'total_price' => $booking->total_price,
        'status' => $booking->status,
        'selected_add_ons' => $booking->selected_add_ons,
        'customer' => $booking->customer ? [
            'id' => $booking->customer->id,
            'name' => $booking->customer->name,
            'email' => $booking->customer->email,
        ] : null,
        'package' => $booking->package ? [
            'id' => $booking->package->id,
            'name' => $booking->package->name,
        ] : null,
        'payment' => $booking->payment ? [
            'id' => $booking->payment->id,
            'status' => $booking->payment->status,
            'payment_method' => $booking->payment->payment_method,
            'payment_proof' => $booking->payment->payment_proof,
            'notes' => $booking->payment->notes,
            'rejection_reason' => $booking->payment->rejection_reason,
        ] : null,
    ];

    return response()->json([
        'booking' => $bookingData
    ]);
})->middleware('auth')->name('api.booking.show');

// API untuk mendapatkan booking per tanggal dengan status (untuk admin calendar)
Route::get('/api/admin/bookings-by-date', function () {
    if (!\Illuminate\Support\Facades\Auth::user()->isAdmin()) {
        abort(403);
    }

    $bookings = \App\Booking::whereIn('status', ['pending', 'confirmed', 'on_progress'])
        ->where('booking_date', '>=', now()->format('Y-m-d'))
        ->select('id', 'booking_date', 'status')
        ->get()
        ->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        })
        ->map(function ($dateBookings) {
            // Ambil status pertama (jika ada multiple booking di tanggal yang sama, ambil yang pertama)
            $firstBooking = $dateBookings->first();
            return [
                'status' => $firstBooking->status,
                'booking_id' => $firstBooking->id,
                'count' => $dateBookings->count()
            ];
        });

    return response()->json([
        'bookings' => $bookings
    ]);
})->middleware('auth')->name('api.admin.bookings-by-date');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Booking
Route::middleware('auth')->group(function () {
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/status', [BookingController::class, 'updateStatus'])->name('booking.updateStatus');
});

// Payment
Route::middleware('auth')->group(function () {
    Route::get('/payment/create/{bookingId}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/{bookingId}', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/{id}/print', [PaymentController::class, 'print'])->name('payment.print');
    Route::post('/payment/{id}/verify', [PaymentController::class, 'verify'])->name('payment.verify');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');

    // WINWIN Makeup Profile Management
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Portfolio Management
    Route::get('/portfolio', [AdminController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio', [AdminController::class, 'storePortfolio'])->name('portfolio.store');
    Route::put('/portfolio/{id}', [AdminController::class, 'updatePortfolio'])->name('portfolio.update');
    Route::delete('/portfolio/{id}', [AdminController::class, 'deletePortfolio'])->name('portfolio.delete');

    // Packages Management
    Route::get('/packages', [AdminController::class, 'packages'])->name('packages');
    Route::post('/packages', [AdminController::class, 'storePackage'])->name('packages.store');
    Route::put('/packages/{id}', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::delete('/packages/{id}', [AdminController::class, 'deletePackage'])->name('packages.delete');

    // Package Add-Ons Management
    Route::post('/addons', [AdminController::class, 'storeAddOn'])->name('addons.store');
    Route::put('/addons/{id}', [AdminController::class, 'updateAddOn'])->name('addons.update');
    Route::delete('/addons/{id}', [AdminController::class, 'deleteAddOn'])->name('addons.delete');

    // Global Add-Ons Management
    Route::get('/global-addons', [AdminController::class, 'addons'])->name('global-addons');
    Route::post('/global-addons', [AdminController::class, 'storeGlobalAddOn'])->name('global-addons.store');
    Route::put('/global-addons/{id}', [AdminController::class, 'updateGlobalAddOn'])->name('global-addons.update');
    Route::delete('/global-addons/{id}', [AdminController::class, 'deleteGlobalAddOn'])->name('global-addons.delete');
});
