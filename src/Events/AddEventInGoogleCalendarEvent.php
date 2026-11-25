<?php

namespace App\Modules\Appointment\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddEventInGoogleCalendarEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $appointmentMaster;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($appointment, $appointmentMaster)
    {
        $this->appointment = $appointment;
        $this->appointmentMaster = $appointmentMaster;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
