<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EticketMail extends Mailable
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
            subject: 'E-Ticket International Flight - '.$this->booking->booking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.eticket_notification',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(storage_path('app/public/'.$this->booking->pdf_path))
                ->as('E-Ticket-'.$this->booking->booking_code.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
