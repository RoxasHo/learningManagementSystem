<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModeratorRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $moderator;
    public $superuser;

    public function __construct($moderator, $superuser)
    {
        $this->moderator = $moderator;
        $this->superuser = $superuser;
    }

    public function build()
    {
        return $this->view('emails.moderatorRequestEmail')
        ->with([
            'moderatorName' => $this->moderator->name,
            'superuserName' => $this->superuser->name,
            'user' => $this->moderator->user,
            'approveUrl' => route('superuser.approveModerator', ['id' => $this->moderator->moderatorID]),
            'rejectUrl' => route('superuser.rejectModerator', ['id' => $this->moderator->moderatorID]),
            ]);
}
}
