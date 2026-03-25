<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Persistence\Doctrine\Repository;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\UserId;
use App\Identity\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;
use App\Identity\Infrastructure\Persistence\Doctrine\Mapper\UserMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctrineUser>
 */
class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry $registry,
        private UserMapper $userMapper,
    ) {
        parent::__construct($registry, DoctrineUser::class);
        $this->em = $this->getEntityManager();
    }

    public function add(User $user): void
    {
        $this->em->persist($this->userMapper->toDoctrine($user));
    }

    public function findById(UserId $id): ?User
    {
        return $this->mapToDomain(
            $this->find($id->value()),
        );
    }

    public function findByEmail(Email $email): ?User
    {
        return $this->mapToDomain(
            $this->findOneBy(['email' => $email->value()]),
        );
    }

    private function mapToDomain(?DoctrineUser $entity): ?User
    {
        return $entity ? $this->userMapper->toDomain($entity) : null;
    }
}
