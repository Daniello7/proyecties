<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyContactVisitorMail extends Mailable implements ShouldQueue
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
            subject: 'Notify Contact Visitor',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notify-contact-visitor',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
