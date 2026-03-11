<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Roles
{
    /** @var array<strict, true */
    private array $roles;


    /**
     * @param string[] $roles
     */
    public function __construct(array $roles)
    {
        if ($roles === []) {
            throw new InvalidArgumentException('Roles cannot be empty');
        }
    }
}
