<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use App\Identity\Domain\Enum\Role;
use InvalidArgumentException;

final readonly class Roles
{
    /** @var string[] */
    private array $roles;


    /**
     * @param array<string|Role> $roles
     */
    public function __construct(array $roles)
    {
        if ($roles === []) {
            throw new InvalidArgumentException('Roles cannot be empty');
        }

        $normalized = [];

        foreach ($roles as $role) {
            if ($role instanceof Role) {
                $normalized[] = $role;
                continue;
            }

            if (!is_string($role) || $role === '') {
                throw new InvalidArgumentException('Each role must be a non-empty string');
            }

            $normalized[] = Role::fromString($role);
        }

        $this->roles = array_values(array_unique(array_map(
            static fn(Role $role): string => $role->value,
            $normalized
        )));
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }

    public function has(Role $role): bool
    {
        return in_array($role->value, $this->roles, true);
    }

    /**
     * @return Role[]
     */
    public function rolesAsEnum(): array
    {
        return array_map(
            static fn(string $role): Role => Role::fromString($role),
            $this->roles
        );
    }
}
