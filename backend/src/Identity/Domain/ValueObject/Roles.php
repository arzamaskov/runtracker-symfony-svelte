<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Roles
{
    /** @var string[] */
    private array $roles;


    /**
     * @param string[] $roles
     */
    public function __construct(array $roles)
    {
        if ($roles === []) {
            throw new InvalidArgumentException('Roles cannot be empty');
        }

        foreach ($roles as $role) {
            if (!is_string($role) || $role === '') {
                throw new InvalidArgumentException('Each role must be a non-empty string');
            }
        }

        $this->roles = array_values(array_unique($roles));
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }
}
