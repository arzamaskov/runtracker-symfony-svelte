<?php

declare(strict_types=1);

namespace App\Identity\Presentation\Api\Controller;

use App\Identity\Application\Command\RegisterUser\RegisterUserCommand;
use App\Identity\Domain\ValueObject\UserId;
use App\Identity\Presentation\Api\Request\RegisterUserRequest;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register', name: 'identity_register', methods: [Request::METHOD_POST])]
final class RegisterUserAction
{
    public function __construct(private readonly CommandBusInterface $commandBus) {}

    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        $registerUserRequest = new RegisterUserRequest($payload['email'], $payload['password']);
        $command = new RegisterUserCommand($registerUserRequest->email, $registerUserRequest->password);

        /** @var UserId $userId */
        $userId = $this->commandBus->execute($command);

        return new JsonResponse(
            [
                'success' => true,
                'data' => [
                    'id' => $userId->value(),
                ],
            ],
            Response::HTTP_CREATED,
        );
    }
}
