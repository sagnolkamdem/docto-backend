<?php

namespace Modules\Document\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Emails\NewAppointmentMail;
use Modules\Appointment\Entities\Appointment;
use Modules\User\Entities\User;

class NewDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::document-new')
            ->to($this->receiver->email, $this->receiver->first_name)
            ->subject('Nouveau document')
            ->attach('img/logo.png', [
                'as' => 'logo.png',
                'mime' => 'image/png',
            ])
            ->with([
                'user' => $this->receiver,
            ]);
    }
}
