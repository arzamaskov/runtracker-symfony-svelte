<?php

declare(strict_types=1);

namespace App\Identity\Domain\Factory;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Exception\DuplicateEmailException;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;

final readonly class UserFactory
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function create(Email $email, PasswordHash $password): User
    {
        if ($this->repository->findByEmail($email)) {
            throw new DuplicateEmailException();
        }

        $id = UserId::generate();

        return new User(
            id: $id,
            email: $email,
            passwordHash: $password,
        );
    }
}
