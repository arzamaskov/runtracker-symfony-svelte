<?php

declare(strict_types=1);

namespace App\Tests\Integration\Identity\Infrastructure\Persistence\Doctrine\Repository;

use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Infrastructure\Persistence\Doctrine\Repository\DoctrineUserRepository;
use App\Tests\Factory\DomainUserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DoctrineUserRepositoryTest extends WebTestCase
{
    private UserRepositoryInterface $repository;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();
        $container = static::getContainer();
        /** @var UserRepositoryInterface $repository */
        $repository = $container->get(DoctrineUserRepository::class);
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        $this->repository = $repository;
        $this->em = $em;
    }

    public function test_user_added_successfully(): void
    {
        // Arrange
        $user = DomainUserFactory::createOne();

        // Act
        $this->repository->add($user);
        $this->em->flush();
        $this->em->clear();

        // Assert
        $found = $this->repository->findById($user->id());

        self::assertNotNull($found);
        self::assertTrue($found->id()->equals($user->id()));
        self::assertTrue($found->email()->equals($user->email()));
        self::assertTrue($found->passwordHash()->equals($user->passwordHash()));
        self::assertSame($user->role(), $found->role());
    }

    public function test_find_by_email_returns_domain_user(): void
    {
        // Arrange
        $user = DomainUserFactory::createOne();

        $this->repository->add($user);
        $this->em->flush();
        $this->em->clear();

        // Act
        $found = $this->repository->findByEmail($user->email());

        // Assert
        self::assertNotNull($found);
        self::assertTrue($found->id()->equals($user->id()));
        self::assertTrue($found->email()->equals($user->email()));
    }

    public function test_find_by_email_returns_null_when_user_does_not_exist(): void
    {
        // Act
        $found = $this->repository->findByEmail(Email::from('missing@example.com'));

        // Assert
        self::assertNull($found);
    }
}
