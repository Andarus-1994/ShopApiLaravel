<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $code;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        //
        $this->code = $request['code'];
        $this->user = $request['user'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))->subject("Password Reset - Shop App")->markdown('mail.userResetPassword');
    }
}
