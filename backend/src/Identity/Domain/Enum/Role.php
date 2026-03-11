<?php

declare(strict_types=1);

namespace App\Identity\Domain\Enum;

enum Role: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';

    public static function fromString(string $role): self
    {
        return self::from(strtoupper($role));
    }
}
