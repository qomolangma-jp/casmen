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
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('面接URLのお知らせ - CASMEN')
                    ->view('emails.interview-link')
                    ->with([
                        'entry' => $this->entry,
                        'interviewUrl' => $this->interviewUrl,
                    ]);
    }
}
