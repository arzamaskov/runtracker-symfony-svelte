<?php

declare(strict_types=1);

namespace App\Identity\Application\Command\RegisterUser;

use App\Identity\Application\Security\PasswordHasherInterface;
use App\Identity\Domain\Factory\UserFactory;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\ValueObject\Email;
use App\Identity\Domain\ValueObject\PasswordHash;
use App\Identity\Domain\ValueObject\UserId;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Persistence\FlusherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private PasswordHasherInterface $passwordHasher,
        private UserFactory $factory,
        private FlusherInterface $flusher,
    ) {}

    public function __invoke(RegisterUserCommand $command): UserId
    {
        $hashedPassword = $this->passwordHasher->hash($command->password);

        $user = $this->factory->create(
            Email::from($command->email),
            PasswordHash::from($hashedPassword),
        );

        $this->repository->add($user);
        $this->flusher->flush();

        return $user->id();
    }
}
