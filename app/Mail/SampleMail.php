<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SampleMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $content;
    /**
     * Create a new message instance.
     */
    public function __construct($content)
    {
        $this->content = $content;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->content['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sample',
        );

    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
        // return [
        //     Attachment::fromStorage('/private/mds/pdf-exports/mh9BwqhlAspS15zgfGgEVttrqFs6IURjIxDR0adg.pdf')
        //         ->as('booking_confirmation.pdf')
        //         ->withMime('application/pdf'),
        // ];
    }
}
