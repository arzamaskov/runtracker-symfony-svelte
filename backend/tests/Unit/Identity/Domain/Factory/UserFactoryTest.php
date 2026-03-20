<?php

declare(strict_types=1);

namespace App\Tests\Unit\Identity\Domain\Factory;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Enum\Role;
use App\Identity\Domain\Exception\DuplicateEmailException;
use App\Identity\Domain\Factory\UserFactory;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function test_create_returns_user_with_provided_email_and_password(): void
    {
        $email = Email::from('runner@example.com');
        $passwordHash = PasswordHash::from(password_hash('secret', PASSWORD_BCRYPT));

        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $factory = new UserFactory($repository);

        $user = $factory->create($email, $passwordHash);

        self::assertInstanceOf(User::class, $user);
        self::assertTrue($user->email()->equals($email));
        self::assertTrue($user->password()->equals($passwordHash));
        self::assertSame(Role::USER, $user->role());
        self::assertInstanceOf(UserId::class, $user->id());
    }

    public function test_create_throws_when_email_already_exists(): void
    {
        $email = Email::from('runner@example.com');
        $passwordHash = PasswordHash::from(password_hash('secret', PASSWORD_BCRYPT));
        $existingUser = new User(
            id: UserId::generate(),
            email: $email,
            passwordHash: $passwordHash,
        );

        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($existingUser);

        $factory = new UserFactory($repository);

        $this->expectException(DuplicateEmailException::class);

        $factory->create($email, $passwordHash);
    }
}
