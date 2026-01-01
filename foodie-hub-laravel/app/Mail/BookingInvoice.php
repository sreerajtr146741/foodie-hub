<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;

class BookingInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dining Completed - Your Invoice',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bookings.invoice',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => Pdf::loadView('pdfs.invoice', ['booking' => $this->booking])->output(), 'Invoice-' . $this->booking->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
