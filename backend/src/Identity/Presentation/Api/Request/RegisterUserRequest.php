<?php

declare(strict_types=1);

namespace App\Identity\Presentation\Api\Request;

final readonly class RegisterUserRequest
{
    public function __construct(public string $email, public string $password) {}
}
