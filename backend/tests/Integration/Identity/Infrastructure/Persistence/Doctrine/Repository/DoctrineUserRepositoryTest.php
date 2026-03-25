<?php

declare(strict_types=1);

namespace App\Tests\Integration\Identity\Infrastructure\Persistence\Doctrine\Repository;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Enum\Role;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;
use App\Identity\Infrastructure\Persistence\Doctrine\Repository\DoctrineUserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineUserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private Connection $connection;
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);
        /** @var DoctrineUserRepository $repository */
        $repository = $container->get(DoctrineUserRepository::class);

        $this->entityManager = $entityManager;
        $this->connection = $this->entityManager->getConnection();
        $this->repository = $repository;

        $this->ensureSchema();
        $this->connection->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }

        $this->entityManager->clear();
        $this->entityManager->close();

        parent::tearDown();
    }

    public function test_add_persists_user_and_find_by_id_returns_domain_user(): void
    {
        $user = $this->createUser('runner@example.com');

        $this->repository->add($user);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $found = $this->repository->findById($user->id());

        self::assertInstanceOf(User::class, $found);
        self::assertTrue($found->id()->equals($user->id()));
        self::assertTrue($found->email()->equals($user->email()));
        self::assertTrue($found->passwordHash()->equals($user->passwordHash()));
        self::assertSame(Role::USER, $found->role());
    }

    public function test_find_by_id_returns_null_when_user_does_not_exist(): void
    {
        $found = $this->repository->findById(UserId::generate());

        self::assertNull($found);
    }

    public function test_find_by_email_returns_domain_user(): void
    {
        $user = $this->createUser('find-by-email@example.com');

        $this->repository->add($user);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $found = $this->repository->findByEmail($user->email());

        self::assertInstanceOf(User::class, $found);
        self::assertTrue($found->id()->equals($user->id()));
        self::assertTrue($found->email()->equals($user->email()));
        self::assertTrue($found->passwordHash()->equals($user->passwordHash()));
        self::assertSame(Role::USER, $found->role());
    }

    public function test_find_by_email_returns_null_when_user_does_not_exist(): void
    {
        $found = $this->repository->findByEmail(Email::from('missing@example.com'));

        self::assertNull($found);
    }

    private function createUser(string $email): User
    {
        return new User(
            id: UserId::generate(),
            email: Email::from($email),
            passwordHash: PasswordHash::from(password_hash('secret', PASSWORD_BCRYPT)),
        );
    }

    private function ensureSchema(): void
    {
        $this->connection->executeStatement('CREATE SCHEMA IF NOT EXISTS identity');
        $this->connection->executeStatement(
            'CREATE TABLE IF NOT EXISTS identity.users (
                id UUID NOT NULL,
                email VARCHAR(255) NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                role VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )',
        );
        $this->connection->executeStatement(
            'CREATE UNIQUE INDEX IF NOT EXISTS uniq_identity_users_email ON identity.users (email)',
        );
    }
}
