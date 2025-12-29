<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/admin/checkins', [CheckinController::class, 'index']);
    Route::get('/admin/checkins/scan/{token}', [CheckinController::class, 'scan']);
});

Route::middleware(['auth'])->post(
    '/admin/checkins/scan-weez',
    [CheckinController::class, 'scanWeezevent']
)->name('checkins.scan.weez');



require __DIR__.'/auth.php';