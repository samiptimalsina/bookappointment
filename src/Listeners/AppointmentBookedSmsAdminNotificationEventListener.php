<?php

namespace App\Modules\Appointment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Appointment\Events\AppointmentBookedSmsAdminNotificationEvent;
use App\Helpers\Misc;
use Carbon\Carbon;
use Twilio;

class AppointmentBookedSmsAdminNotificationEventListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param AppointmentBookedSmsAdminNotificationEvent $event
     * @return void
     */
    public function handle(AppointmentBookedSmsAdminNotificationEvent $event)
    {
        $appointment = $event->appointment;
        $receiver = env('ADMIN_MOBILE_NO');
        $message = "Appointment Received:\n" . $appointment->first_name . " " . $appointment->last_name . " been booked an appointment for " . Misc::usFormatDate($appointment->appointment_date) . " - " . date("h:i a", strtotime($appointment->start_time)) . "\nThank You";
        try {
            $sendMassage = Twilio::message($receiver, $message);
            $smsLog['receiver'] = $sendMassage->to;
            $smsLog['body'] = $sendMassage->body;
            $smsLog['api_response'] = $sendMassage;
            $smsLog['sms_id'] = $sendMassage->sid;
            $smsLog['sms_status'] = $sendMassage->status;
            $smsLog['message_id'] = $sendMassage->messagingServiceSid;
            $smsLog['api_version'] = $sendMassage->apiVersion;
            $smsLog['date_sent'] = $sendMassage->dateSent;
            $smsLog['direction'] = $sendMassage->direction;
            $smsLog['error_code'] = $sendMassage->errorCode;
            $smsLog['error_message'] = $sendMassage->errorMessage;
            $smsLog['from'] = $sendMassage->from;
            $smsLog['num_media'] = $sendMassage->numMedia;
            $smsLog['num_segments'] = $sendMassage->numSegments;
            $smsLog['price'] = $sendMassage->price;
            $smsLog['price_unit'] = $sendMassage->priceUnit;
            $smsLog['account_sid'] = $sendMassage->accountSid;
            $smsLog['uri'] = $sendMassage->uri;
            $appointment->smsable()->create($smsLog);
        } catch (\Exception $e) {
        }
    }
}
