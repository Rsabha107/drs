<?php

namespace App\Services;

use App\Mail\DynamicTemplateMail;
use App\Models\Gms\EmailLog;
use App\Models\Gms\EmailTemplate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class TemplatedEmailService
{
    public function findTemplate(string $key, ?string $locale = null): EmailTemplate
    {
        $locale = $locale ?: app()->getLocale();

        $tpl = EmailTemplate::where('key', $key)
            ->where('locale', $locale)
            ->where('active', true)
            ->first();

        // fallback to en
        if (!$tpl && $locale !== 'en') {
            $tpl = EmailTemplate::where('key', $key)
                ->where('locale', 'en')
                ->where('active', true)
                ->first();
        }

        if (!$tpl) {
            throw new RuntimeException("Email template not found/active: {$key} ({$locale})");
        }

        // return $tpl;
        return $tpl->load('attachments');
    }

    public function logQueued(EmailTemplate $tpl, array $meta, array $payload): EmailLog
    {
        return EmailLog::create([
            'template_key' => $tpl->key,
            'locale'       => $tpl->locale,
            'to'           => $meta['to'] ?? null,
            'cc'  => isset($meta['cc'])
                ? (is_array($meta['cc']) ? implode(',', $meta['cc']) : $meta['cc'])
                : null,
            'bcc' => isset($meta['bcc'])
                ? (is_array($meta['bcc']) ? implode(',', $meta['bcc']) : $meta['bcc'])
                : null,
            'payload'      => $payload,
            'attachments'  => $tpl->attachments->map(fn($a) => [
                                'disk'=>$a->disk,'path'=>$a->path,'name'=>$a->original_name,'size'=>$a->size
                            ])->values()->all(),
            'status'       => 'queued',
        ]);
    }

    public function sendNow(string $key, array $payload, array $meta = [], ?string $locale = null): array
    {
        Log::info("Sending templated email: {$key} to " . ($meta['to'] ?? 'N/A'));
        Log::debug('Payload: ' . json_encode($payload));
        Log::debug('Meta: ' . json_encode($meta));
        Log::debug('Locale: ' . ($locale ?? 'N/A'));

        $tpl = $this->findTemplate($key, $locale);

        // (optional) enforce variable allowlist
        [$found, $unknown] = EmailTemplateRenderer::validateVariables($tpl);
        if (!empty($unknown)) {
            throw new RuntimeException('Unknown variables in template: ' . implode(', ', $unknown));
        }

        $log = $this->logQueued($tpl, $meta, $payload);

        $subject = EmailTemplateRenderer::render($tpl->subject, $payload);
        $body = EmailTemplateRenderer::wrapRtl(
            EmailTemplateRenderer::render($tpl->body, $payload),
            $tpl->locale
        );

        $log->update([
            'subject' => $subject,
            'body'    => $body,
        ]);

        try {
            $mailer = Mail::to($meta['to'] ?? null);

            if (!empty($meta['cc']))  $mailer->cc($meta['cc']);
            if (!empty($meta['bcc'])) $mailer->bcc($meta['bcc']);

            $mailer->send(new DynamicTemplateMail($tpl, $payload, $log));

            $log->update([
                'status'  => 'sent',
                'sent_at' => now(),
                'error'   => null,
            ]);
            return [
                'success' => true,
                'status'  => 'sent',
                'message' => 'Email sent successfully.',
            ];
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ]);

            return [
                'success' => true,
                'status'  => 'sent',
                'message' => 'Email sent successfully.',
            ];
        }

        // return $log;
    }
}
