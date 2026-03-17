<?php

declare(strict_types=1);

namespace App\Identity\Domain\Factory;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\Roles;
use App\Identity\Domain\ValueObject\UserId;
use InvalidArgumentException;

final class UserFactory
{
    public function __construct(private readonly UserRepositoryInterface $repository) {}

    public function create(Email $email, PasswordHash $password, Roles $roles): User
    {
        if ($this->repository->findByEmail($email)) {
            throw new InvalidArgumentException('User with this email already exists.');
        }

        $id = UserId::generate();

        return new User(
            id: $id,
            email: $email,
            passwordHash: $password,
            roles: $roles,
        );
    }
}
