<?php

namespace App\Jobs;

namespace App\Jobs;

use App\Mail\BulkMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBulkEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;

    public $subject;

    public $content;

    public function __construct($users, $subject, $content)
    {
        $this->users = $users;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function handle()
    {
        foreach ($this->users as $user) {
            // $content = "Hello, this is a bulk email!";
            // $subject = "Fatafatin Offer";
            Mail::to($user)->send(new BulkMail($this->content, $this->subject));
        }
    }
}
