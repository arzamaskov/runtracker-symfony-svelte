<?php

declare(strict_types=1);

namespace App\Identity\Domain\Enum;

use InvalidArgumentException;

enum Role: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';

    /**
     * @throws InvalidArgumentException When the role is unknown or unsupported.
     */
    public static function fromString(string $role): self
    {
        return self::tryFrom(strtoupper($role))
            ?? throw new InvalidArgumentException(sprintf('Unknown role "%s".', $role));
    }
}
