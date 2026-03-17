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
     * @param string[] $roles
     */
    protected function __construct(array $roles)
    {
        $this->roles = self::normalize($roles);
    }

    /**
     * @param Role[] $roles
     */
    public static function fromEnums(array $roles): self
    {
        return new self(
            array_map(
                static fn(Role $role): string => $role->value,
                $roles,
            ),
        );
    }

    /**
     * @param string[] $roles
     */
    public static function fromStrings(array $roles): self
    {
        return new self($roles);
    }

    public function has(Role $role): bool
    {
        return in_array($role->value, $this->roles, true);
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }

    /**
     * @return Role[]
     */
    public function rolesAsEnum(): array
    {
        return array_map(
            static fn(string $role): Role => Role::fromString($role),
            $this->roles,
        );
    }

    public function add(Role $role): self
    {
        if ($this->has($role)) {
            return $this;
        }

        $roles = $this->roles;
        $roles[] = $role->value;

        return $this->with($roles);
    }

    public function remove(Role $role): self
    {
        $index = array_search($role->value, $this->roles, true);
        if ($index === false) {
            return $this;
        }

        $roles = $this->roles;
        unset($roles[$index]);

        return $this->with($roles);
    }

    /**
     * @param string[] $roles
     *
     * @return string[]
     */
    private static function normalize(array $roles): array
    {
        if ($roles === []) {
            throw new InvalidArgumentException('Roles cannot be empty');
        }

        $values = [];

        foreach ($roles as $role) {
            if ($role === '') {
                throw new InvalidArgumentException('Each role must be a non-empty string');
            }

            $values[] = Role::fromString($role)->value;
        }

        return array_values(array_unique($values));
    }


    /**
     * @param string[] $roles
     */
    private function with(array $roles): self
    {
        return new self($roles);
    }
}
