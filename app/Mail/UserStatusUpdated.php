<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $statusMessage;

    public function __construct($user, $statusMessage)
    {
        $this->user = $user;
        $this->statusMessage = $statusMessage;
    }

    public function build()
    {
        return $this->view('emails.user_status_updated')
                    ->with([
                        'user' => $this->user,
                    ]);
    }
}
