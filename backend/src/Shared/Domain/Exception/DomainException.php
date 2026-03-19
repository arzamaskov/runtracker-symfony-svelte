<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use DomainException as PHPDomainException;
use Stringable;
use Throwable;

abstract class DomainException extends PHPDomainException implements LocalizeErrorInterface
{
    /**
     * @param array<string, scalar|Stringable|null> $translationParameters
     */
    public function __construct(
        private readonly array $translationParameters = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($this->translationKey(), 0, $previous);
    }

    abstract public function errorCode(): string;

    public function translationKey(): string
    {
        return 'errors.' . $this->errorCode();
    }

    /** @return array<string, int|float|string|bool|Stringable|null>  */
    public function translationParameters(): array
    {
        return $this->translationParameters;
    }
}
