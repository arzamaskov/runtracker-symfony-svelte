<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Persistence\Doctrine\Entity;

class DoctrineUser
{
    public function __construct(
        private string $id,
        private string $email,
        private string $passwordHash,
        private string $role,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function role(): string
    {
        return $this->role;
    }

    public function changeEmail(string $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function changePasswordHash(string $newPasswordHash): void
    {
        $this->passwordHash = $newPasswordHash;
    }

    public function changeRole(string $newRole): void
    {
        $this->role = $newRole;
    }
}
