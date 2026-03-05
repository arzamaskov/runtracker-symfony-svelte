<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Email extends StringValueObject
{
    private function __construct(string $email)
    {
        parent::__construct($email);
    }

    public static function from(string $email): self
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }

        [$local, $domain] = explode('@', $email, 2);

        return new self($local . '@' . mb_strtolower($domain));
    }
}
