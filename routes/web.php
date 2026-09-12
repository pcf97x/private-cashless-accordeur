<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ReservationAdminController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\RoomRateController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EcosystemPartnerController;
use App\Http\Controllers\Admin\PricingProfileController;
use App\Http\Controllers\Admin\ReservationOptionController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/espaces', [PublicController::class, 'espaces'])->name('espaces');
Route::get('/ecosysteme', [PublicController::class, 'ecosysteme'])->name('ecosysteme');
Route::get('/planning', [PublicController::class, 'planning'])->name('planning');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactStore'])->name('contact.store');

Route::get('/acces', [AccessController::class, 'create']);
Route::post('/acces', [AccessController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Reservation (public)
|--------------------------------------------------------------------------
*/

Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
Route::get('/reservation/{room}', [ReservationController::class, 'show'])->name('reservation.show');
Route::post('/reservation/price', [ReservationController::class, 'checkAvailability'])->name('reservation.price');
Route::post('/reservation/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservation.checkAvailability');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/reservation/{reservation}/pay', [ReservationController::class, 'pay'])->name('reservation.pay');
Route::get('/reservation/success/{reservation}', [ReservationController::class, 'success'])->name('reservation.success');
Route::get('/reservation/cancel/{reservation}', [ReservationController::class, 'cancel'])->name('reservation.cancel');

/*
|--------------------------------------------------------------------------
| Stripe Webhook (CSRF exempt via bootstrap/app.php)
|--------------------------------------------------------------------------
*/

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Checkins / Pointage
    Route::get('/admin/checkins', [CheckinController::class, 'index'])->name('checkins.index');
    Route::get('/admin/checkins/scan/{token}', [CheckinController::class, 'scan'])->name('admin.checkins.scan');
    Route::post('/admin/checkins/scan-weez', [CheckinController::class, 'scanWeezevent'])->name('checkins.scan.weez');
    Route::get('/admin/checkins/{code}/edit', [CheckinController::class, 'edit'])->name('checkins.edit');
    Route::post('/admin/checkins/{code}', [CheckinController::class, 'update'])->name('checkins.update');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');

    // Salles, Créneaux, Tarifs
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{room}/toggle', [RoomController::class, 'toggle'])->name('rooms.toggle');
    Route::resource('time-slots', TimeSlotController::class)->except(['show']);
    Route::resource('rates', RoomRateController::class);

    // Reservations
    Route::get('/reservations', [ReservationAdminController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationAdminController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationAdminController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationAdminController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/resend-email', [ReservationAdminController::class, 'resendEmail'])->name('reservations.resendEmail');
    Route::post('/reservations/{reservation}/cancel', [ReservationAdminController::class, 'cancelAndRefund'])->name('reservations.cancel');

    // Profils tarifaires, Options, Ecosystème
    Route::resource('pricing-profiles', PricingProfileController::class)->except(['show']);
    Route::resource('options', ReservationOptionController::class)->except(['show']);
    Route::resource('ecosystem', EcosystemPartnerController::class)->except(['show']);

    // Rapports de présence
    Route::get('/rapports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/rapports/csv', [ReportController::class, 'exportCsv'])->name('reports.csv');
    Route::get('/rapports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});

// Utilisateurs (admin uniquement)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
