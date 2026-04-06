<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Security;

use App\Identity\Application\Security\PasswordHasherInterface;

final readonly class PhpPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }
}
