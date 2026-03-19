<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Stringable;

interface LocalizeErrorInterface
{
    public function errorCode(): string;
    public function translationKey(): string;
    /** @return array<string, scalar|Stringable|null> */
    public function translationParameters(): array;
}
