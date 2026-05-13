<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Tesmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tes Email dari KosQueenSalon',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tesmail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}