<?php

use App\Modules\Appointment\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::group(['prefix' => 'appointment', 'as' => 'appointment.'], function () {
        Route::get('', [AppointmentController::class, 'index'])->name('index');
        Route::get('book/{id}', [AppointmentController::class, 'create'])->name('book');
        Route::post('book/{id}', [AppointmentController::class, 'store'])->name('book.post');
        Route::get('success/{id}', [AppointmentController::class, 'success'])->name('success');
        Route::post('get-available-slot', [AppointmentController::class, 'getAvailableSlot'])->name('get-available-slot');
        Route::post('get-available-days', [AppointmentController::class, 'getAvailableDays'])->name('get-available-days');
        Route::get('cancelled', [AppointmentController::class, 'cancelSuccess'])->name('cancelled');
        Route::get('cancel/{id}', [AppointmentController::class, 'cancelAppointment'])->name('cancel');
    });
});
