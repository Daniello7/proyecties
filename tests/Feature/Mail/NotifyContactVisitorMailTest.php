<?php

use App\Mail\NotifyContactVisitorMail;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

beforeEach(function () {
    $this->message = 'Test message content';
    $this->mail = new NotifyContactVisitorMail($this->message);
});

it('can be instantiated with a message', function () {
    expect($this->mail)
        ->toBeInstanceOf(NotifyContactVisitorMail::class)
        ->message->toBe($this->message);
});

it('has correct envelope configuration', function () {
    $envelope = $this->mail->envelope();

    expect($envelope)
        ->toBeInstanceOf(Envelope::class)
        ->and($envelope->subject)->toBe('Notify Contact Visitor');
});

it('has correct content configuration', function () {
    $content = $this->mail->content();

    expect($content)
        ->toBeInstanceOf(Content::class)
        ->and($content->markdown)->toBe('emails.notify-contact-visitor');
});

it('has no attachments', function () {
    expect($this->mail->attachments())
        ->toBeArray()
        ->toBeEmpty();
});

it('implements queue interface', function () {
    expect($this->mail)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});

it('renders the correct view with message', function () {
    $this->mail
        ->assertHasSubject('Notify Contact Visitor')
        ->assertSeeInHtml($this->message);
});

it('uses markdown for rendering', function () {
    $content = $this->mail->content();

    expect($content->markdown)
        ->not()->toBeNull()
        ->toBe('emails.notify-contact-visitor')
        ->and($content->html)->toBeNull()
        ->and($content->view)->toBeNull();
});