<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject('VMS Report Confirmation'.' - ' .$this->details['event'] . ' | ' . $this->details['venue']. ' | ' . $this->details['report_date'])
            ->view('emails.new_report_creation')
            // ->from('mds@scqa0.onmicrosoft.com', 'VMS')
            ->attach(storage_path('app/private/vms/pdf-exports/' . $this->details['filename']), [
                // 'as' => 'booking_confirmation.pdf',
                'mime' => 'application/pdf',
            ])
            ->with([
                'details' => $this->details,
            ]);
    }
    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'MDS Confirmation for a New Booking Request',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.new_booking',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

}
