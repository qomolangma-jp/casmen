<?php

namespace App\Mail;

use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $entry;
    public $interviewUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Entry $entry, string $interviewUrl)
    {
        $this->entry = $entry;
        $this->interviewUrl = $interviewUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $companyName = $this->entry->user ? $this->entry->user->shop_name : 'CASMEN';
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject("【{$companyName}】録画面接のご案内")
                    ->view('emails.interview-link')
                    ->with([
                        'entry' => $this->entry,
                        'interviewUrl' => $this->interviewUrl,
                        'companyName' => $companyName,
                    ]);
    }
}
