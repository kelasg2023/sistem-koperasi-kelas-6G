<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    public $email;
    public $mailSubject;
    public $mailContent;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $mailSubject, $mailContent)
    {
        $this->email = $email;
        $this->mailSubject = $mailSubject;
        $this->mailContent = $mailContent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\SendEmailMail($this->mailSubject, $this->mailContent));
    }
}
