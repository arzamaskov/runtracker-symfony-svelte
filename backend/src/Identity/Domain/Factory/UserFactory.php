<?php

declare(strict_types=1);

namespace App\Identity\Domain\Factory;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\UserId;

final class UserFactory
{
    public function __construct(private readonly UserRepositoryInterface $repository) {}

    public function create(Email $email, string $password): User
    {
        $id = UserId::generate();

        return new User(
            id: $id,
            email: $email,
            passwordHash: $password,
        );
    }
}
