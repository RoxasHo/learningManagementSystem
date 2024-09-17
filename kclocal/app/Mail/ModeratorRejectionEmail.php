<?php
//ModeratorRejectionEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModeratorRejectionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $moderator;
    public $rejectionReason;

    public function __construct($moderator,$rejectionReason)
    {
        $this->moderator = $moderator;
        $this->rejectionReason = $rejectionReason;
    }

    public function build()
    {
        return $this->view('emails.moderatorRejectionEmail')
                    ->with([
                        'moderatorName' => $this->moderator->name,
                        'rejectionReason' => $this->rejectionReason,
                    ]);
    }
}
