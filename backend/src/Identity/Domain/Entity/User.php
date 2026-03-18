<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity;

use App\Identity\Domain\Enum\Role;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;

class User
{
    public function __construct(
        private readonly UserId $id,
        private Email $email,
        private PasswordHash $passwordHash,
        private Role $role = Role::USER,
    ) {}

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function role(): Role
    {
        return $this->role;
    }

    public function changeEmail(Email $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function changePassword(PasswordHash $newPasswordHash): void
    {
        $this->passwordHash = $newPasswordHash;
    }
}
