<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Persistence\Doctrine\Mapper;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Enum\Role;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;
use App\Identity\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;

final class UserMapper
{
    public function toDomain(DoctrineUser $record): User
    {
        return new User(
            id: UserId::from($record->id()),
            email: Email::from($record->email()),
            passwordHash: PasswordHash::from($record->passwordHash()),
            role: Role::fromString($record->role()),
        );
    }

    public function toDoctrine(User $user): DoctrineUser
    {
        return new DoctrineUser(
            id: $user->id()->value(),
            email: $user->email()->value(),
            passwordHash: $user->passwordHash()->value(),
            role: $user->role()->value,
        );
    }
}
