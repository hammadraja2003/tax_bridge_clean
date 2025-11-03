<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistarionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $loginUrl;

    public function __construct($name, $email, $loginUrl)
    {
        $this->name = $name;
        $this->email = $email;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Welcome to TaxBridge')
                    ->view('emails.user_registration');
    }
}
