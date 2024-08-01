<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use DateTimeImmutable;

readonly final class CreateUserCommand
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public DateTimeImmutable $birthdate,
        public string $email,
        public string $phoneNumber,
        public string $city,
        public string $state,
        public string $zip,
        public string $ssn,
    ) {
    }
}