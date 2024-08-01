<?php

declare(strict_types=1);

namespace App\User\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class User
{
    public function __construct(
        private UuidInterface $id,
        private string $firstName,
        private string $lastName,
        private DateTimeImmutable $birthdate,
        private string $email,
        private string $phoneNumber,
        private string $city,
        private string $state,
        private string $zip,
        private string $ssn,
        private int $fico,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setBirthDate(DateTimeImmutable $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getBirthdate(): DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setSsn(string $ssn): void
    {
        $this->ssn = $ssn;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }

    public function setFico(int $fico): void
    {
        $this->fico = $fico;
    }

    public function getFico(): int
    {
        return $this->fico;
    }
}