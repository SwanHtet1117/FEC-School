<?php

namespace App\Notifications;

use App\Notifications\Concerns\EmbedsFecLogo;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    use EmbedsFecLogo;

    public function toMail($notifiable): MailMessage
    {
        $viewData = [
            'name' => $notifiable->name,
            'verificationUrl' => $this->verificationUrl($notifiable),
            'logoUrl' => $this->fecLogoUrl(),
        ];

        return $this->embedFecLogo((new MailMessage)
            ->subject('Verify your FEC International School email')
            ->view('emails.verify-email', $viewData)
            ->text('emails.verify-email-text', $viewData));
    }
}
