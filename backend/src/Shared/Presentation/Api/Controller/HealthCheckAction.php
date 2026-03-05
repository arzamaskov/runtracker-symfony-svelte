<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/healthcheck', name: 'healthcheck', methods: [Request::METHOD_GET])]
class HealthCheckAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['status' => 'OK']);
    }
}
