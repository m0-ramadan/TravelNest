<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        $this->booking->loadMissing(['client', 'package', 'items', 'travelers']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New booking: {$this->booking->booking_number}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-booking-admin');
    }

    public function attachments(): array
    {
        return [];
    }
}
