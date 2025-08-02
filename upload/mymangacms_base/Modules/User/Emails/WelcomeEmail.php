<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var UserInterface
     */
    public $id;

    /**
     * @var
     */
    public $activationCode;

    public function __construct($id, $activationCode)
    {
        $this->id = $id;
        $this->activationCode = $activationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::emails.welcome')
            ->subject(trans('user::messages.front.auth.welcome_title'));
    }
}
