<?php

namespace Modules\Appointment\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Entities\Appointment;
use Modules\User\Entities\User;

class EndAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $receiver, Appointment $appointment)
    {
        $this->receiver = $receiver;
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::appointment-solve')
            ->to($this->receiver->email, $this->receiver->first_name)
            ->subject('Rendez vous terminé')
            ->attach('img/logo.png', [
                'as' => 'logo.png',
                'mime' => 'image/png',
            ])
            ->with([
                'user' => $this->receiver,
                'appointment' => $this->appointment,
            ]);
    }
}
