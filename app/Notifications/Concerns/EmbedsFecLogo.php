<?php

namespace App\Notifications\Concerns;

use Illuminate\Notifications\Messages\MailMessage;
use Symfony\Component\Mime\Part\DataPart;

trait EmbedsFecLogo
{
    private const FEC_LOGO_CONTENT_ID = 'fec-logo@fec-school';

    protected function fecLogoUrl(): string
    {
        return 'cid:'.self::FEC_LOGO_CONTENT_ID;
    }

    protected function embedFecLogo(MailMessage $mailMessage): MailMessage
    {
        return $mailMessage->withSymfonyMessage(static function ($message): void {
            $message->addPart(
                DataPart::fromPath(public_path('image/logo_01.png'), 'fec-logo.png', 'image/png')
                    ->asInline()
                    ->setContentId(self::FEC_LOGO_CONTENT_ID)
            );
        });
    }
}
