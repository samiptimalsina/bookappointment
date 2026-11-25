<?php

namespace App\Modules\Appointment\Listeners;

use App\Modules\Appointment\Events\AddEventInGoogleCalendarEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AddEventInGoogleCalendarEventListener
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
     * @param AddEventInGoogleCalendarEvent $event
     * @return void
     */
    public function handle(AddEventInGoogleCalendarEvent $event)
    {
        try {
            $calendarEvent = new Event;
            $calendarEvent->name = 'Appointment With ' . $event->appointment->first_name . ' ' . $event->appointment->last_name;
            $calendarEvent->summary = 'Appointment With ' . $event->appointment->first_name . ' ' . $event->appointment->last_name . ' at ' . $event->appointmentMaster->appointment_date . ' From ' . date("h:i a", strtotime($event->appointmentMaster->start_time)) . ' to ' . date("h:i a", strtotime($event->appointmentMaster->end_time));
            $calendarEvent->description = 'Appointment With ' . $event->appointment->first_name . ' ' . $event->appointment->last_name . ' at ' . $event->appointmentMaster->appointment_date . ' From ' . date("h:i a", strtotime($event->appointmentMaster->start_time)) . ' to ' . date("h:i a", strtotime($event->appointmentMaster->end_time)) . '<br><br>Address : <span style="color:red;">' . $event->appointment->address . ', ' . $event->appointment->zipCode->city->name . ', ' . $event->appointment->zipCode->state->name . ', ' . $event->appointment->zipCode->code . '</span><br><br>Customer Phone : <span stype="color:red;"><a href="tel:' . $event->appointment->phone . '" class="Blondie">' . $event->appointment->phone . '</a></span><br><br>Appointment Type : ' . $event->appointmentMaster->type->name . '<br><br>Message : ' . $event->appointment->message;
            $calendarEvent->startDateTime = Carbon::createFromTimestamp(strtotime($event->appointmentMaster->appointment_date . $event->appointmentMaster->start_time));
            $calendarEvent->endDateTime = Carbon::createFromTimestamp(strtotime($event->appointmentMaster->appointment_date . $event->appointmentMaster->end_time));
            $calendarEvent->iCalUID = $event->appointment->unique_id;
            $calendarEvent->location = $event->appointment->address . ', ' . $event->appointment->zipCode->city->name . ', ' . $event->appointment->zipCode->state->name . ', ' . $event->appointment->zipCode->code;
            $calendarEvent->save();
        } catch (\Exception $e) {
            Log::error('Error in event listener: ' . $e->getMessage());
            return true;
        }
    }
}
