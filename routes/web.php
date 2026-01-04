<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/acces', [AccessController::class, 'create']);
Route::post('/acces', [AccessController::class, 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
   Route::get('/admin/checkins', [CheckinController::class, 'index'])
    ->name('checkins.index');
    Route::get('/admin/checkins/scan/{token}', [CheckinController::class, 'scan']);
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
    [\App\Http\Controllers\Admin\ContactController::class, 'index']
)->name('admin.contacts.index');



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
});
require __DIR__.'/auth.php';