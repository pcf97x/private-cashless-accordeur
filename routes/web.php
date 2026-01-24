<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/acces', [AccessController::class, 'create']);
Route::post('/acces', [AccessController::class, 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
   Route::get('/admin/checkins', [CheckinController::class, 'index'])
    ->name('checkins.index');
   Route::get('/admin/checkins/scan/{token}', [CheckinController::class, 'scan'])
    ->name('admin.checkins.scan');
});




Route::middleware(['auth'])->post(
    '/admin/checkins/scan-weez',
    [CheckinController::class, 'scanWeezevent']
)->name('checkins.scan.weez');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/checkins/{code}/edit', [CheckinController::class, 'edit'])
        ->name('checkins.edit');

    Route::post('/admin/checkins/{code}', [CheckinController::class, 'update'])
        ->name('checkins.update');
});

Route::middleware(['auth'])->get(
    '/admin/contacts',
    [\App\Http\Controllers\admin\ContactController::class, 'index']
)->name('admin.contacts.index');



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('rooms', RoomController::class);
    Route::resource('time-slots', TimeSlotController::class);
    Route::resource('rates', RoomRateController::class);

    Route::get('reservations', [ReservationAdminController::class, 'index'])
        ->name('reservations.index');
});

use App\Http\Controllers\Admin\TimeSlotController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('time-slots', TimeSlotController::class)->except(['show']);
});
use App\Http\Controllers\Admin\RoomRateController;

Route::get('admin/rates', [RoomRateController::class, 'index'])->name('admin.rates.index');
Route::post('admin/rates', [RoomRateController::class, 'store'])->name('admin.rates.store');


use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');

Route::get('/reservation/{room}', [ReservationController::class, 'show'])->name('reservation.show');

Route::post('/reservation/price', [ReservationController::class, 'calculatePrice'])->name('reservation.price');
Route::post('/reservation/price', [ReservationController::class, 'checkAvailability'])
    ->name('reservation.price');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

Route::get('/reservation/{reservation}/pay', [ReservationController::class, 'pay'])->name('reservation.pay');
Route::get('/reservation/success/{reservation}', [ReservationController::class, 'success'])->name('reservation.success');
Route::get('/reservation/cancel/{reservation}', [ReservationController::class, 'cancel'])->name('reservation.cancel');

/**
 * Stripe Webhook (optionnel en local, utile en prod)
 */
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

/**
* Route::post('/reservation/calculate-price', [ReservationController::class, 'calculatePrice'])
*   ->name('reservation.calculatePrice');
 */
Route::post('/reservation/check-availability', [ReservationController::class, 'checkAvailability'])
    ->name('reservation.checkAvailability');





Route::post('/payment-intent', [ReservationController::class, 'createPaymentIntent'])
    ->name('payment.intent');

 



require __DIR__.'/auth.php';
