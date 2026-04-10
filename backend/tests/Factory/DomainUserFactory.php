<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Enum\Role;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<User>
 */
class DomainUserFactory extends ObjectFactory
{
    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'id' => UserId::generate(),
            'email' => Email::from(self::faker()->email()),
            'passwordHash' => PasswordHash::from(
                password_hash(self::faker()->password(), PASSWORD_ARGON2ID),
            ),
            'role' => Role::from('ROLE_USER'),
        ];
    }
}
