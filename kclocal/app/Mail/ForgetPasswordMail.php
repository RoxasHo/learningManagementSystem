<?php
//ForgetPasswordMail
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class ForgetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $resetLink)
    {
        $this->user = $user;
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.forget-password')
                    ->with([
                        'user' => $this->user,
                        'resetLink' => $this->resetLink,
                    ]);
    }
}
