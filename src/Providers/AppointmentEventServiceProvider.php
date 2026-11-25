<?php

namespace App\Modules\Appointment\Providers;

use App\Modules\Appointment\Events\AddEventInGoogleCalendarEvent;
use App\Modules\Appointment\Events\AppointmentBookedSmsAdminNotificationEvent;
use App\Modules\Appointment\Events\AppointmentBookedSmsClientNotificationEvent;
use App\Modules\Appointment\Events\DeleteGoogleCalendarScheduleEvent;
use App\Modules\Appointment\Listeners\AddEventInGoogleCalendarEventListener;
use App\Modules\Appointment\Listeners\AppointmentBookedSmsAdminNotificationEventListener;
use App\Modules\Appointment\Listeners\AppointmentBookedSmsClientNotificationEventListener;
use App\Modules\Appointment\Listeners\DeleteGoogleCalendarScheduleEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AppointmentEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AddEventInGoogleCalendarEvent::class => [
            AddEventInGoogleCalendarEventListener::class,
        ],
        AppointmentBookedSmsClientNotificationEvent::class => [
            AppointmentBookedSmsClientNotificationEventListener::class,
        ],
        AppointmentBookedSmsAdminNotificationEvent::class => [
            AppointmentBookedSmsAdminNotificationEventListener::class,
        ],
        DeleteGoogleCalendarScheduleEvent::class => [
            DeleteGoogleCalendarScheduleEventListener::class,
        ],
    ];
}
