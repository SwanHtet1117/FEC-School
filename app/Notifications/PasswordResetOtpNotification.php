<?php

namespace App\Notifications;

use App\Notifications\Concerns\EmbedsFecLogo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetOtpNotification extends Notification
{
    use Queueable;
    use EmbedsFecLogo;

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
            'logoUrl' => $this->fecLogoUrl(),
        ];

        return $this->embedFecLogo((new MailMessage)
            ->subject('Your FEC password reset code')
            ->view('emails.password-reset-otp', $viewData)
            ->text('emails.password-reset-otp-text', $viewData));
    }
}
