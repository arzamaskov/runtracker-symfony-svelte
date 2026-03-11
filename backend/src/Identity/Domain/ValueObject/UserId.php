<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final readonly class UserId extends StringValueObject
{
    public static function generate(): self
    {
        return new self(Uuid::v7()->toRfc4122());
    }

    public static function from(string $uuid): self
    {
        if (! Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('Invalid UUID');
        }

        return new self($uuid);
    }
}
