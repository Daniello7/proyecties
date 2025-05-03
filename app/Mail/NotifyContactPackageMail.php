<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyContactPackageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notify Contact Package',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notify-contact-package',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
