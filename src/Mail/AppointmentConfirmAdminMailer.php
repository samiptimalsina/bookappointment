<?php

namespace App\Modules\Appointment\Mail;

use App\Helpers\Misc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmAdminMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public $appointmentMaster;

    /**
     * Create a new message instance.
     *
     * @param $appointment
     * @param $appointmentMaster
     */
    public function __construct($appointment, $appointmentMaster)
    {
        $this->appointment = $appointment;
        $this->appointmentMaster = $appointmentMaster;
        $this->to(Misc::getSettingValue('web-master-email'), 'Web Master');
        $this->bcc('developer.kokil@gmail.com', 'Kokil Thapa');
        $this->subject("Appointment Received From Website");
        $this->from(Misc::getSettingValue('from-email'), Misc::getSettingValue('website-name'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('appointment::emails.appointmentConfirmedAdmin');
    }
}
