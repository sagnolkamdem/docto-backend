<?php

namespace Modules\Employee\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Modules\User\Entities\User;

class EmployeeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('employee::employeecreated')
            ->to($this->recipient->email, $this->recipient->first_name)
            ->subject('Bienvenue')
            ->attach('img/logo.png', [
                'as' => 'logo.png',
                'mime' => 'image/png',
            ])
            ->with([
                'user' => $this->recipient,
            ]);
    }
}
