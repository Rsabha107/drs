<?php

namespace App\Mail;

use App\Models\Sps\Profile;
use App\Models\Vapp\VappRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeMailCopy extends Mailable 
//implements ShouldQueue
{
    // use Queueable; //, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $vappRequest;
    public $qrBase64;
    public $qrUrl;

    public function __construct(VappRequest $vappRequest, public $qrFilePath)
    {
        $this->vappRequest = $vappRequest;

        // $this->qrBase64 = base64_encode(QrCode::format('png')->size(200)->generate($qrUrl));
    }


    public function build()
    {
        return $this->subject('Your Request Confirmation')
                    ->view('emails.request_confirmation')
                    ->attach(
                        $this->qrFilePath,
                        [
                            'as' => 'request-confirmation'.$this->vappRequest->request_number.'.png',
                            'mime' => 'image/png',
                        ]
                    );

        // return $this->subject('Your Profile Confirmation')
        //             ->view('sps.emails.profile_confirmation');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
