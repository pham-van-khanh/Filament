<?php

namespace App\Enums;

enum MediaType: string
{
    case Image = 'image';
    case Video = 'video';
    case Audio = 'audio';
    case File = 'file';

    public static function fromMime(?string $mime): self
    {
        return match (true) {
            str_starts_with((string) $mime, 'image/') => self::Image,
            str_starts_with((string) $mime, 'video/') => self::Video,
            str_starts_with((string) $mime, 'audio/') => self::Audio,
            default => self::File,
        };
    }
}

