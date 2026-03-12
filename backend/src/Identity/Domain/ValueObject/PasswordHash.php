<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final readonly class PasswordHash extends StringValueObject
{
    public static function from(string $hash): self
    {
        if ($hash === '') {
            throw new InvalidArgumentException('Password hash cannot be empty');
        }

        return new self($hash);
    }
}
