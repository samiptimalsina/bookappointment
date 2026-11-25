<?php

namespace App\Modules\Appointment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Appointment\Events\AppointmentBookedSmsClientNotificationEvent;
use Twilio;
use App\Helpers\Misc;
use Carbon\Carbon;

class AppointmentBookedSmsClientNotificationEventListener
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
     * @param AppointmentBookedSmsClientNotificationEvent $event
     * @return void
     */
    public function handle(AppointmentBookedSmsClientNotificationEvent $event)
    {
        $appointment = $event->appointment;
        $receiver = '+1' . $appointment->phone;
        $message = "Dear $appointment->first_name,\nYour appointment has been booked for " . Misc::usFormatDate($appointment->appointment_date) . "\nThank You\nPratt Pest Control\nCell: ".env('ADMIN_MOBILE_NO')."";
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
