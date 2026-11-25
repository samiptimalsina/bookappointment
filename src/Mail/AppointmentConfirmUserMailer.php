<?php

namespace App\Modules\Appointment\Mail;

use View;
use Carbon\Carbon;
use App\Helpers\Misc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Properties\TextProperty;
use Spatie\IcalendarGenerator\Enums\EventStatus;

class AppointmentConfirmUserMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public $appointmentMaster;

    /**
     * Create a new message instance.
     *
     * @param $appointment
     * @param $appointmentMaster
     * @param $emailTemplate
     */
    public function __construct($appointment, $appointmentMaster)
    {
        $this->appointment = $appointment;
        $this->appointmentMaster = $appointmentMaster;
        $this->to($appointment->email, $appointment->first_name . ' ' . $appointment->last_name);
        $this->bcc('developer.kokil@gmail.com', 'Kokil Thapa');
        $this->subject("Appointment Confirmation");
        $this->from(Misc::getSettingValue('from-email'), Misc::getSettingValue('website-name'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $eventName = 'Appointment With Pratt Pest Control';

        if (isset($this->appointment->appointmentMaster->type->name))
            $eventName = $this->appointment->appointmentMaster->type->name . ' Appointment With Pratt Pest Control';


        $calendar = Calendar::create()
            ->productIdentifier('prattpestcontrol.com')
            ->event(function (Event $event) use ($eventName) {
                $event
                    ->name('Appointment With Pratt Pest Control')
                    ->description($eventName . $this->appointment->appointmentMaster->appointment_date . ' From ' . date("h:i a", strtotime($this->appointment->appointmentMaster->start_time)) . ' to ' . date("h:i a", strtotime($this->appointment->appointmentMaster->end_time)) . '<br>Address : ' . $this->appointment->address . ', ' . $this->appointment->zipCode->city->name . ', ' . $this->appointment->zipCode->state->name . ', ' . $this->appointment->zipCode->code . '<br>Phone : <a href="tel:' . $this->appointment->phone . '" class="Blondie">' . $this->appointment->phone)
                    ->organizer('info@prattpestcontrol.com', 'Pratt Pest Control')
                    ->attendee($this->appointment->email, $this->appointment->first_name . ' ' . $this->appointment->last_name)
                    ->address($this->appointment->address . ', ' . $this->appointment->zipCode->city->name . ', ' . $this->appointment->zipCode->state->name . ', ' . $this->appointment->zipCode->code)
                    ->startsAt(Carbon::createFromTimestamp(strtotime($this->appointment->appointment_date . $this->appointment->start_time)))
                    ->endsAt(Carbon::createFromTimestamp(strtotime($this->appointment->appointment_date . $this->appointment->end_time)))
                    ->status(EventStatus::confirmed())
                    ->image('https://prattpestcontrol.com/images/logo.png');

            });
        $calendar->appendProperty(TextProperty::create('METHOD', 'REQUEST'));

        return $this->view('appointment::emails.appointmentConfirmedUser')->attachData($calendar->get(), 'appointment.ics', ['mime' => 'text/calendar; charset=UTF-8; method=REQUEST']);
    }
}
