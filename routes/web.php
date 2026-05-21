<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));


/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'notAdmin'])->group(function () {

    Route::get('/dashboard', [BookingController::class, 'dashboard'])
        ->name('dashboard');

    // BOOKING
    Route::prefix('booking')->name('booking.')->group(function () {

        Route::get('/', [BookingController::class, 'userIndex'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');

        Route::get('/{booking}/edit', [BookingController::class, 'userEdit'])->name('edit');
        Route::put('/{booking}', [BookingController::class, 'userUpdate'])->name('update');
        Route::delete('/{booking}', [BookingController::class, 'userDelete'])->name('delete');
    });

    // PAYMENT
    Route::prefix('payment')->name('payment.')->group(function () {

        Route::get('/{transaction}', [PaymentController::class, 'userCreate'])
            ->name('create');

        Route::post('/', [PaymentController::class, 'userStore'])
            ->name('store');
    });

    // QUEUE
    Route::get('/queue', [BookingController::class, 'queue'])
        ->name('queue.index');

    // PROFILE
    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

});


/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'isAdmin'])
    ->name('admin.')
    ->group(function () {

        // DASHBOARD (FIX UTAMA)
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // QUEUE
        Route::get('/queue', [QueueController::class,'index'])->name('queue.index');
        Route::post('/queue/assign', [QueueController::class,'assign'])->name('queue.assign');
        Route::post('/queue/{id}/clear', [QueueController::class,'clear'])->name('queue.clear');

        // BOOKING ADMIN
        Route::prefix('booking')->name('booking.')->group(function () {

            Route::get('/', [AdminBookingController::class, 'index'])->name('index');
            Route::get('/create', [AdminBookingController::class, 'create'])->name('create');
            Route::post('/', [AdminBookingController::class, 'store'])->name('store');

            Route::get('/{booking}/edit', [AdminBookingController::class, 'edit'])->name('edit');
            Route::put('/{booking}', [AdminBookingController::class, 'update'])->name('update');
            Route::delete('/{booking}', [AdminBookingController::class, 'destroy'])->name('delete');

            Route::post('/{booking}/approve', [AdminBookingController::class, 'approve'])->name('approve');
            Route::post('/{booking}/reject', [AdminBookingController::class, 'reject'])->name('reject');

            Route::post('/{booking}/checkin', [AdminBookingController::class, 'checkin'])->name('checkin');
            Route::post('/{booking}/start', [AdminBookingController::class, 'start'])->name('start');
            Route::post('/{booking}/finish', [AdminBookingController::class, 'finish'])->name('finish');
        });

        // MASTER DATA
        Route::resource('services', ServiceController::class);
        Route::resource('users', UserController::class);

        // TRANSACTION
        Route::get('transactions/export', [TransactionController::class, 'export'])
            ->name('transactions.export');

        Route::resource('transactions', TransactionController::class);

        // PAYMENT
        Route::resource('payments', AdminPaymentController::class)
            ->only(['index','update','destroy']);
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';