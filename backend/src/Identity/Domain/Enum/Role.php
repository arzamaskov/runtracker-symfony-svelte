<?php

declare(strict_types=1);

namespace App\Identity\Domain\Enum;

enum Role: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';

    /**
     * @throws \InvalidArgumentException When role is неизвестна/неподдерживаемая.
     */
    public static function fromString(string $role): self
    {
        $normalized = strtoupper($role);
        $value = self::tryFrom($normalized);

        if ($value === null) {
            throw new \InvalidArgumentException(sprintf('Unknown role "%s".', $role));
        }

        return $value;
    }
}
