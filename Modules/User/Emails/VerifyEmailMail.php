<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Modules\User\Entities\User;

class VerifyEmailMail extends Mailable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $recipient;

    /**
     * Create a new message instance.
     *
     * @param User $recipient
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
        $token = Str::random(50);
        $encrypted = Crypt::encryptString($this->recipient->id);

        return $this->view('authentication::emailverify')
            ->to($this->recipient->email, $this->recipient->first_name)
            ->subject('Verify Email')
            ->attach('img/logo.png', [
                    'as' => 'logo.png',
                    'mime' => 'image/png',
                ])
//            ->embedData(file_get_contents('img/logo.png'), 'logo.png', 'image/png')
            ->with([
                'user' => $this->recipient,
                'user_id' => $encrypted,
                'token' => $token,
                'is_admin' => $this->recipient->isAdmin(),
            ]);
    }
}
