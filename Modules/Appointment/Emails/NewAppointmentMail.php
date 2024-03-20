<?php

namespace Modules\Appointment\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Entities\Appointment;
use Modules\Practician\Entities\Practician;
use Modules\User\Entities\User;

class NewAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiver, Appointment $appointment, $practician = false)
    {
        $this->receiver = $receiver;
        $this->appointment = $appointment;
        $this->practician = $practician;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->practician) {
            return $this->view('practician::appointment-new')
                ->to($this->receiver->email, $this->receiver->first_name)
                ->subject('Nouveau rendez-vous')
                ->attach('img/logo.png', [
                    'as' => 'logo.png',
                    'mime' => 'image/png',
                ])
                ->with([
                    'user' => $this->receiver,
                    'appointment' => $this->appointment,
                ]);
        }
        return $this->view('user::appointment-new')
            ->to($this->receiver->email, $this->receiver->first_name)
            ->subject('Nouveau rendez-vous')
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
