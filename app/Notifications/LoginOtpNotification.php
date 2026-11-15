<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $code,
        public readonly int $expiresInMinutes,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $viewData = [
            'name' => $notifiable->name,
            'code' => $this->code,
            'expiresInMinutes' => $this->expiresInMinutes,
            'logoUrl' => asset('image/logo_01.png'),
        ];

        return (new MailMessage)
            ->subject('Your FEC sign-in code')
            ->view('emails.login-otp', $viewData)
            ->text('emails.login-otp-text', $viewData);
    }
}
