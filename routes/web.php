<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Models\Airline;
use App\Models\Flight;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $popularFlights = Flight::with(['airline', 'originAirport', 'destinationAirport'])
        ->where('available_seats', '>', 0)
        ->orderBy('price')
        ->take(3)
        ->get();
    $partnerAirlines = Airline::query()
        ->whereIn('code', ['EK', 'GA', 'SQ', 'QR', 'NH'])
        ->orderBy('name')
        ->get();

    return view('welcome', compact('popularFlights', 'partnerAirlines'));
})->name('home');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/checkout/{id}', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::get('/verify-ticket/{code}', [BookingController::class, 'verifyTicket'])->name('booking.verify');

Route::get('/dashboard', [BookingController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/payment/finish', [BookingController::class, 'paymentFinish'])->name('payment.finish');
    Route::get('/payment/success', [BookingController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{id}/ticket', [BookingController::class, 'downloadTicket'])->name('booking.ticket');
    Route::get('/booking/eticket/{id}', [BookingController::class, 'generatePdf'])->name('booking.eticket');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
