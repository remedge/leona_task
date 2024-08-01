<?php

declare(strict_types=1);

namespace App\User\Infrastructure\FileStorage;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileStorageUserRepository implements UserRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private string $dbPath,
    ) {
    }

    public function save(User $user): void
    {
        $userArray = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'birthdate' => $user->getBirthdate()->getTimestamp(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'city' => $user->getCity(),
            'state' => $user->getState(),
            'zip' => $user->getZip(),
            'ssn' => $user->getSsn(),
            'fico' => $user->getFico(),
        ];

        // Save user to separate file
        $this->filesystem->dumpFile(
            $this->dbPath . '/users/' . $user->getId() . '.json',
            json_encode($userArray)
        );
    }

    public function findById(UuidInterface $id): ?User
    {
        $userFile = $this->dbPath . '/users/' . $id . '.json';

        if (! $this->filesystem->exists($userFile)) {
            return null;
        }

        /** @var array{id: string, firstName: string, lastName: string, birthdate: int, email: string, phoneNumber: string, city: string, state: string, zip: string, ssn: string, fico: int} $userArray */
        $userArray = json_decode(file_get_contents($userFile), true);

        return new User(
            id: Uuid::fromString($userArray['id']),
            firstName:  $userArray['firstName'],
            lastName: $userArray['lastName'],
            birthdate: (new DateTimeImmutable())->setTimestamp($userArray['birthdate']),
            email: $userArray['email'],
            phoneNumber: $userArray['phoneNumber'],
            city: $userArray['city'],
            state: $userArray['state'],
            zip: $userArray['zip'],
            ssn: $userArray['ssn'],
            fico: $userArray['fico'],
        );
    }
}