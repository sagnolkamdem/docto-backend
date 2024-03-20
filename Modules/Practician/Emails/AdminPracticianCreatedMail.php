<?php

namespace Modules\Practician\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Practician\Entities\Practician;
use Modules\User\Entities\User;

class AdminPracticianCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(Practician $practician)
    {
        $this->recipient = User::role('admin')->first();
        $this->pratician = $practician;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('practician::newpractician')
            ->to($this->recipient->email, $this->recipient->first_name)
            ->subject('Nouveau Praticien')
            ->attach('img/logo.png', [
                'as' => 'logo.png',
                'mime' => 'image/png',
            ])
            ->with([
                'user' => $this->pratician,
            ]);
    }
}
