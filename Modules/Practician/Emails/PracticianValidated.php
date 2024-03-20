<?php

namespace Modules\Practician\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Practician\Entities\Practician;

class PracticianValidated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Practician $recipient, string $password)
    {
        $this->recipient = $recipient;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('practician::practicianvalidated')
            ->to($this->recipient->email, $this->recipient->first_name)
            ->subject('Compte validé')
            ->attach('img/logo.png', [
                'as' => 'logo.png',
                'mime' => 'image/png',
            ])
            ->with([
                'user' => $this->recipient,
                'password' => $this->password
            ]);
    }
}
