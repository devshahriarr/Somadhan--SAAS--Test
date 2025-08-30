<?php

namespace App\Jobs;

use App\Mail\CustomerSendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCustomerEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($recipient, $data)
    {
        $this->recipient = $recipient;
        $this->data = $data;
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recipient)->send(new CustomerSendEmail($this->data));
    }
}
