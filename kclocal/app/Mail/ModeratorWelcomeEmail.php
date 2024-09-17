<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModeratorWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $moderator;
    public $superuser;

    /**
     * Create a new message instance.
     *
     * @param $moderator
     * @param $superuser
     */
    public function __construct($moderator, $superuser)
    {
        $this->moderator = $moderator;
        $this->superuser = $superuser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.moderatorWelcomeEmail')
                    ->with([
                        'moderatorName' => $this->moderator->name,
                        'superuserName' => $this->superuser->name,
                    ]);
    }
}
