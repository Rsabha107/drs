<?php

namespace App\Services;

use App\Models\Gms\EmailTemplate;

class EmailTemplateRenderer
{
    public static function extractPlaceholders(string $content): array
    {
        preg_match_all('/\{([a-zA-Z0-9_\.]+)\}/', $content, $m);
        return array_values(array_unique($m[1] ?? []));
    }

    public static function render(string $content, array $data): string
    {
        $replace = [];

        foreach (self::extractPlaceholders($content) as $key) {
            $value = data_get($data, $key, '');
            $replace['{' . $key . '}'] = is_scalar($value)
                ? (string) $value
                : json_encode($value);
        }

        return strtr($content, $replace);
    }

    public static function validateVariables(EmailTemplate $template): array
    {
        $found = array_unique(array_merge(
            self::extractPlaceholders($template->subject),
            self::extractPlaceholders($template->body),
        ));

        $allowed = $template->allowed_variables ?? [];
        $unknown = array_values(array_diff($found, $allowed));

        return [$found, $unknown];
    }

    public static function wrapRtl(string $html, string $locale): string
    {
        if ($locale !== 'ar') {
            return $html;
        }

        return '<div dir="rtl" style="text-align:right;font-family:Tahoma,Arial,sans-serif">'
            . $html .
            '</div>';
    }
}
