<?php

if (! function_exists('clean_html')) {
    function clean_html(?string $html): string
    {
        if (! $html) {
            return '';
        }

        static $purifier = null;

        if (! $purifier) {
            $config = \HTMLPurifier_Config::createDefault();

            // Không ghi cache vào vendor, chuyển sang storage của Laravel
            $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));

            $config->set('HTML.Allowed', implode(',', [
                'p', 'br', 'strong', 'b', 'em', 'i', 'u',
                'a[href|title|target|rel]',
                'ul', 'ol', 'li',
                'blockquote', 'code', 'pre',
                'h2', 'h3', 'h4',
            ]));

            $config->set('Attr.AllowedFrameTargets', ['_blank']);

            $purifier = new \HTMLPurifier($config);
        }

        return $purifier->purify($html);
    }
}

if (! function_exists('memory_style')) {
    function memory_style(array $style): string
    {
        return collect($style)
            ->filter(fn ($value, $key) => is_scalar($value) && str_starts_with((string) $key, '--'))
            ->map(fn ($value, $key) => "{$key}: {$value}")
            ->implode('; ');
    }
}

