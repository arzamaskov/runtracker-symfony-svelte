<?php

declare(strict_types=1);

namespace App\Identity\Domain\Exception;

final class DuplicateEmailException extends IdentityDomainException
{
    public function errorCode(): string
    {
        return 'identity.email_already_in_use';
    }
}
