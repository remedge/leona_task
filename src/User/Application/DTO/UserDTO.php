<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class UserDTO
{
    public function __construct(
        public UuidInterface $id,
        public string $firstName,
        public string $lastName,
        public DateTimeImmutable $birthdate,
        public string $email,
        public string $phoneNumber,
        public string $city,
        public string $state,
        public string $zip,
        public string $ssn,
        public int $fico,
    ) {
    }
}