<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity;

use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\UserId;

class User
{
    public function __construct(
        private readonly UserId $id,
        private Email $email,
        private string $passwordHash,
        private array $roles = ['ROLE_USER'],
    ) {}

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->passwordHash;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function changeEmail(Email $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function changePassword(string $newPassword): void
    {
        $this->passwordHash = $newPassword;
    }
}
