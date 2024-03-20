<?php

namespace Modules\Appointment\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Transformers\AppointmentRessource;
use Modules\User\Entities\User;

class CancelAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $receiver, Appointment $appointment, string $motif = null)
    {
        $this->receiver = $receiver;
        $this->motif = $motif;
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::appointment-cancel')
        ->to($this->receiver->email, $this->receiver->first_name)
        ->subject('Rendez vous annulé')
        ->attach('img/logo.png', [
            'as' => 'logo.png',
            'mime' => 'image/png',
        ])
        ->with([
            'user' => $this->receiver,
            'motif' => $this->motif,
            'appointment' => $this->appointment,
        ]);
    }
}
