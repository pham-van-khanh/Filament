<?php

namespace App\Enums;

enum PostVisibility: string
{
    case Public = 'public';
    case Private = 'private';
    case Unlisted = 'unlisted';
    case Password = 'password';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public',
            self::Private => 'Private',
            self::Unlisted => 'Unlisted',
            self::Password => 'Password protected',
        };
    }
}

