<?php

namespace App\Mail;

use App\Models\Gms\EmailLog;
use App\Models\Gms\EmailTemplate;
use App\Services\EmailTemplateRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class DynamicTemplateMail extends Mailable
{
    public function __construct(
        public EmailTemplate $template,
        public array $data,
        public ?EmailLog $log = null
    ) {}

    public function build()
    {
        $subject = EmailTemplateRenderer::render($this->template->subject, $this->data);
        $body = EmailTemplateRenderer::render($this->template->body, $this->data);
        $body = EmailTemplateRenderer::wrapRtl($body, $this->template->locale);

        $mailable = $this->subject($subject)->html($body);

        foreach ($this->template->attachments as $att) {
            $mailable->attach(Storage::disk($att->disk)->path($att->path), [
                'as' => $att->original_name,
            ]);
        }

        return $mailable;
    }
}
