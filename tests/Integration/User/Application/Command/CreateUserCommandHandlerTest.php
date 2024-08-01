<?php

declare(strict_types=1);

namespace App\Tests\Integration\User\Application\Command;

use App\User\Application\Command\CreateUserCommand;
use App\User\Domain\UserRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserCommandHandlerTest extends KernelTestCase
{
    private MessageBusInterface $messageBus;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->messageBus = $container->get(MessageBusInterface::class);
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function testHandle(): void
    {
        $this->messageBus->dispatch(new CreateUserCommand(
            firstName: 'John',
            lastName: 'Doe',
            birthdate: new DateTimeImmutable('1990-01-01'),
            email: 'jonny@mail.com',
            phoneNumber: '1234567890',
            city: 'New York',
            state: 'NY',
            zip: '10001',
            ssn: '123-45-6789',
        ));

        $user = $this->userRepository->findById(Uuid::fromString('00000000-0000-0000-0000-000000000000'));
        $this->assertNotNull($user);
        $this->assertSame('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals(new DateTimeImmutable('1990-01-01'), $user->getBirthdate());
        $this->assertEquals('jonny@mail.com', $user->getEmail());
        $this->assertEquals('1234567890', $user->getPhoneNumber());
        $this->assertEquals('New York', $user->getCity());
        $this->assertEquals('NY', $user->getState());
        $this->assertEquals('10001', $user->getZip());
        $this->assertEquals('123-45-6789', $user->getSsn());
    }
}