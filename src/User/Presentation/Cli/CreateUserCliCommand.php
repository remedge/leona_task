<?php

declare(strict_types=1);

namespace App\User\Presentation\Cli;

use App\User\Application\Command\CreateUserCommand;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'user:create', description: 'Create a new user')]
class CreateUserCliCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        do {
            /** @var string $firstName */
            $firstName = $helper->ask($input, $output, new Question('Please enter the first name: '));
        } while (empty($firstName));
        do {
            /** @var string $lastName */
            $lastName = $helper->ask($input, $output, new Question('Please enter the last name: '));
        } while (empty($lastName));
        do {
            $birthdate = false;
            /** @var string|null $birthdateString */
            $birthdateString = $helper->ask($input, $output, new Question('Please enter the birthdate (dd.mm.yyyy): '));
            if ($birthdateString !== null) {
                /** @var DateTimeImmutable|false $birthdate */
                $birthdate = DateTimeImmutable::createFromFormat('d.m.Y', $birthdateString);
                if ($birthdate !== false) {
                    $birthdate = $birthdate->setTime(0, 0);
                }
            }
        } while ($birthdate === false);
        do {
            /** @var string|null $email */
            $email = $helper->ask($input, $output, new Question('Please enter valid email: '));
        } while ($email === null || ! filter_var($email, FILTER_VALIDATE_EMAIL));
        do {
            /** @var string|null $phoneNumber */
            $phoneNumber = $helper->ask($input, $output, new Question('Please enter valid phone number (only digits): '));
        } while ($phoneNumber === null || ! preg_match('/^\d+$/', $phoneNumber));
        do {
            /** @var string|null $city */
            $city = $helper->ask($input, $output, new Question('Please enter the city: '));
        } while ($city === null);
        do {
            /** @var string|null $state */
            $state = $helper->ask($input, $output, new Question('Please enter the state (2 uppercase letters): '));
        } while ($state === null || ! preg_match('/^[A-Z]{2}$/', $state));
        do {
            /** @var string|null $zip */
            $zip = $helper->ask($input, $output, new Question('Please enter the zip (5 digits): '));
        } while ($zip === null || ! preg_match('/^\d{5}$/', $zip));
        do {
            /** @var string|null $ssn */
            $ssn = $helper->ask($input, $output, new Question('Please enter the ssn (9 digits): '));
        } while ($ssn === null || ! preg_match('/^\d{9}$/', $ssn));

        $this->messageBus->dispatch(new CreateUserCommand(
            firstName: $firstName,
            lastName: $lastName,
            birthdate: $birthdate,
            email: $email,
            phoneNumber: $phoneNumber,
            city: $city,
            state: $state,
            zip: $zip,
            ssn: $ssn,
        ));

        $output->writeln('User successfully created');
        return Command::SUCCESS;
    }
}