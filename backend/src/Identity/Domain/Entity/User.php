<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity;

use App\Identity\Domain\Enum\Role;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\Roles;
use App\Identity\Domain\ValueObject\UserId;

class User
{
    public function __construct(
        private readonly UserId $id,
        private Email $email,
        private ?PasswordHash $passwordHash,
        private Roles $roles,
    ) {}

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): ?PasswordHash
    {
        return $this->passwordHash;
    }

    public function roles(): Roles
    {
        return $this->roles;
    }

    public function assignRole(Role $role): void {}

    public function revokeRole(Role $role): void {}

    public function hasRole(Role $role): bool
    {
        return $this->roles->has($role);
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
