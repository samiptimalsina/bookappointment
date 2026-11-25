<?php

namespace App\Modules\Appointment\Mail;

use App\Helpers\Misc;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use View;

class AppointmentCancelNotifier extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     *
     * @param $appointment
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
        $this->to($appointment->email, $appointment->first_name . ' ' . $appointment->last_name);
        $this->bcc('developer.kokil@gmail.com', 'Kokil Thapa');
        $this->subject("Appointment Cancelled");
        $this->from(Misc::getSettingValue('from-email'), Misc::getSettingValue('website-name'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('appointment::emails.appointmentCanceled');
    }
}
