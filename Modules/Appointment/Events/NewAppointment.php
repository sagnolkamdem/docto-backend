<?php

namespace Modules\Appointment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Transformers\AppointmentNotifRessource;
use Modules\Appointment\Transformers\AppointmentRessource;
use Modules\User\Entities\User;
use Illuminate\Broadcasting\Channel;

class NewAppointment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Appointment $message)
    {
        $this->user = $user;
        $this->message = new AppointmentNotifRessource($message);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ["appointment"];
    }

    public function broadcastAs()
    {
        return 'new-appointment';
    }
}
