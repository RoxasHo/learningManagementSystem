<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRejectionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rejectionReason;

    public function __construct($user, $rejectionReason)
    {
        $this->user = $user;
        $this->rejectionReason = $rejectionReason;
    }

    public function build()
    {
        return $this->view('emails.user_rejection')
                    ->with([
                        'user' => $this->user,
                        'rejectionReason' => $this->rejectionReason,
                    ]);
    }
}
