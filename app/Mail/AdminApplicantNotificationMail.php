<?php

namespace App\Mail;

use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminApplicantNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $entry;
    public $type;
    public $data;

    public function __construct(Entry $entry, string $type, array $data = [])
    {
        $this->entry = $entry;
        $this->type = $type;
        $this->data = $data;
    }

    public function build()
    {
        $subject = '';
        $view = '';

        switch ($this->type) {
            case 'review_request':
                $subject = '【CASMEN】応募者の評価をお願いいたします（動画確認）';
                $view = 'emails.admin.review-request';
                break;
            case 'rejection_sent':
                $subject = '【CASMEN】応募者へ不採用通知を送信しました';
                $view = 'emails.admin.rejection-sent';
                break;
            case 'resend_url':
                $subject = '【CASMEN】面接URLの再送をお願いいたします';
                $view = 'emails.admin.resend-url';
                break;
            case 'url_sent':
                $subject = '【CASMEN】応募者へ面接URLを送信しました';
                $view = 'emails.admin.url-sent';
                break;
            case 'url_not_sent':
                $subject = '【CASMEN】応募者への面接URL送信をお願いいたします';
                $view = 'emails.admin.url-not-sent';
                break;
        }

        return $this->subject($subject)
                    ->text($view)
                    ->with($this->data);
    }
}
