<?php

namespace App\Mail\Transports;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class ResendTransport extends AbstractTransport
{
    public function __construct(
        protected readonly string $apiKey,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $envelope = $message->getEnvelope();

        $headers = [];
        foreach ($email->getHeaders()->all() as $header) {
            $name = strtolower($header->getName());
            if (! in_array($name, ['from', 'to', 'cc', 'bcc', 'reply-to', 'sender', 'subject', 'content-type'], true)) {
                $headers[$header->getName()] = $header->getBodyAsString();
            }
        }

        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            $preparedHeaders = $attachment->getPreparedHeaders();
            $disposition = $preparedHeaders->getHeaderBody('Content-Disposition');

            $item = [
                'content_type' => $preparedHeaders->get('Content-Type')->getBody(),
                'content' => base64_encode($attachment->getBody()),
                'filename' => $preparedHeaders->getHeaderParameter('Content-Disposition', 'filename'),
            ];

            if ($disposition === 'inline' && $attachment->hasContentId()) {
                $item['content_id'] = $attachment->getContentId();
            }

            $attachments[] = $item;
        }

        $recipients = array_values(array_filter(
            $envelope->getRecipients(),
            fn ($address) => ! in_array($address->toString(), array_merge(
                array_map(fn ($item) => $item->toString(), $email->getCc()),
                array_map(fn ($item) => $item->toString(), $email->getBcc()),
            ), true),
        ));

        $payload = [
            'from' => $envelope->getSender()->toString(),
            'to' => array_map(fn ($address) => $address->toString(), $recipients),
            'cc' => array_map(fn ($address) => $address->toString(), $email->getCc()),
            'bcc' => array_map(fn ($address) => $address->toString(), $email->getBcc()),
            'reply_to' => array_map(fn ($address) => $address->toString(), $email->getReplyTo()),
            'subject' => $email->getSubject(),
            'html' => $email->getHtmlBody(),
            'text' => $email->getTextBody(),
            'headers' => $headers,
            'attachments' => $attachments,
        ];

        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->post('https://api.resend.com/emails', array_filter(
                $payload,
                fn ($value) => $value !== null && $value !== [] && $value !== '',
            ));

        if (! $response->successful()) {
            $details = $response->json('message')
                ?? $response->body()
                ?? 'Unknown Resend API error';

            throw new TransportException(
                sprintf('Request to Resend API failed. Reason: %s.', $details),
                $response->status(),
            );
        }

        $email->getHeaders()->addHeader('X-Resend-Email-ID', (string) $response->json('id'));
    }

    public function __toString(): string
    {
        return 'resend';
    }
}
