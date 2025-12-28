<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('【CASMEN】パスワード再設定のご案内')
            ->line('パスワード再設定のリクエストを受け付けました。')
            ->line('下記のボタンより、パスワードの再設定を行ってください。')
            ->action('パスワードを再設定する', url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('本パスワード再設定リンクの有効期限は 60分 です。')
            ->line('有効期限が切れた場合は、再度パスワード再設定を行ってください。')
            ->line('------------------------------------------------------------')
            ->line('本メールにお心当たりがない場合やご不明な点がございましたら、下記へお問い合わせください。')
            ->line('【お問い合わせはこちら】')
            ->line('support@casmen.jp')
            ->line('【CASMEN】')
            ->line('https://casmen.jp/');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
