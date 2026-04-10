<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Identity\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<DoctrineUser>
 */
class DoctrineUserFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return DoctrineUser::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->unique()->safeEmail(),
            'passwordHash' => password_hash('secret123', PASSWORD_ARGON2ID),
            'role' => 'ROLE_USER',
        ];
    }
}
