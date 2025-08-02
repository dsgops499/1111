<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var UserInterface
     */
    public $id;

    /**
     * @var
     */
    public $code;

    public function __construct($id, $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::emails.reminder')
            ->subject(trans('user::messages.front.auth.reset_password'));
    }
}
