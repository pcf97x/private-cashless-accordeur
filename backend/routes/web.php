<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckinController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/pointage', [CheckinController::class, 'create'])->name('checkins.create');
Route::post('/pointage', [CheckinController::class, 'store'])->name('checkins.store');

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


require __DIR__.'/auth.php';