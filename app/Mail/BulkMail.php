<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    public $subject;

    public function __construct($content, $subject)
    {
        $this->content = $content;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('pos.crm.email.compose')
            ->with([
                'content' => $this->content,
            ]);
    }
}
