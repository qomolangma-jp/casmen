<?php

namespace App\Mail;

use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $entry;

    /**
     * Create a new message instance.
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【もえなび！】アルバイトに関するお知らせ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $shopName = $this->entry->user ? $this->entry->user->shop_name : 'もえなび！';
        return new Content(
            view: 'emails.rejection-notification',
            with: [
                'entry' => $this->entry,
                'candidateName' => $this->entry->name,
                'email' => $this->entry->email,
                'shopName' => $shopName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
