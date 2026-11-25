<?php

namespace App\Modules\Appointment\Listeners;

use App\Modules\Appointment\Events\DeleteGoogleCalendarScheduleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\GoogleCalendar\Event;

class DeleteGoogleCalendarScheduleEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DeleteGoogleCalendarScheduleEvent $event
     * @return void
     */
    public function handle(DeleteGoogleCalendarScheduleEvent $event)
    {
        $appointments = Event::get();
        $appointment = $appointments->first(function ($item) use ($event) {
            return $item->googleEvent->iCalUID == $event->appointment->unique_id;
        });
        if (isset($appointment)) {
            $appointment->delete();
        }
    }
}
