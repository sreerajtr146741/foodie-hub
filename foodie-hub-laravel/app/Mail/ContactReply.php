<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public $replyText;

    public function __construct(ContactMessage $contactMessage, $replyText)
    {
        $this->contactMessage = $contactMessage;
        $this->replyText = $replyText;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Your Message to Food Court',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
