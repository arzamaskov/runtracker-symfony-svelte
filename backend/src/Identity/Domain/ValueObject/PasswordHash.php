<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final readonly class PasswordHash extends StringValueObject
{
    public static function from(string $hash): self
    {
        if (trim($hash) === '') {
            throw new InvalidArgumentException('Password hash cannot be empty');
        }

        $info = password_get_info($hash);

        $algo = $info['algo'] ?? null;

        if ($algo === null || $algo === 0) {
            throw new InvalidArgumentException('Invalid password hash');
        }


        return new self($hash);
    }
}
