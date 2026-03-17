<?php

declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function add(User $user): void;
    public function findByEmail(Email $email): ?User;
}
