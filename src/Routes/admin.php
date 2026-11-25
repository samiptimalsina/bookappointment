<?php

use App\Modules\Appointment\Http\Controllers\Admin\AppointmentController;
use App\Modules\Appointment\Http\Controllers\Admin\AppointmentMasterController;
use App\Modules\Appointment\Http\Controllers\Admin\AppointmentSetupController;
use App\Modules\Appointment\Http\Controllers\Admin\AppointmentTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::group(['prefix' => 'appointment', 'as' => 'appointment.'], function () {
        Route::post('types/change-status', [AppointmentTypeController::class, 'changeStatus'])->name('types.change-status');
        Route::post('types/sort', [AppointmentTypeController::class, 'sortOrder'])->name('types.sort');
        Route::get('master-setup/specific-day', [AppointmentMasterController::class, 'specificDay'])->name('master-setup.specific-day');
        Route::post('master-setup/specific-day/change-status', [AppointmentMasterController::class, 'specificDayChangeStatus'])->name('master-setup.specific-day.change-status');
        Route::post('master-setup/specific-day/bulk-change-status', [AppointmentMasterController::class, 'specificDayBulkChangeStatus'])->name('master-setup.specific-day.bulk-change-status');
        Route::get('appointment/getAppointments', [AppointmentController::class, 'getAppointments'])->name('appointment.getAppointments');
        Route::post('appointment/markCompleted', [AppointmentController::class, 'markCompleted'])->name('appointment.completed');
        Route::get('appointment/cancel/{id}', [AppointmentController::class, 'cancelAppointment'])->name('appointment.cancel');
        Route::resources([
            'types' => AppointmentTypeController::class,
            'setup' => AppointmentSetupController::class,
            'appointment' => AppointmentController::class,
            'master-setup' => AppointmentMasterController::class,
        ]);
    });
});
