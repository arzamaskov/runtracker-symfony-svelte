<?php

declare(strict_types=1);

namespace App\Identity\Application\Security;

interface PasswordHasherInterface
{
    /**
     * @return string hashed password
     */
    public function hash(string $plainPassword): string;
}
