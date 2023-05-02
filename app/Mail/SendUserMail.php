<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendUserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $token;
    public $frontend_url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        //
        $this->user = $request['user'];
        $this->token = $request['token'];
        $this->frontend_url = $request['frontend_url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))->subject("Email Verification - Shop App")->markdown('mail.userAuthConfirmation');
    }
}
