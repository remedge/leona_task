<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Presentation\Cli;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class UpdateUserCliCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $dbPath = self::getContainer()->getParameter('db_path');
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile(
            filename: $dbPath . '/users/00000000-0000-0000-0000-000000000000.json',
            content: '{"id":"00000000-0000-0000-0000-000000000000","firstName":"John","lastName":"Doe","birthdate":639792000,"email":"mail@mail.com","phoneNumber":"111222333","city":"New York","state":"NY","zip":"12345","ssn":"123456789","fico":200}'
        );

        $command = $application->find('user:update');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['Alice', 'Cooper', '21.08.1996', 'alice@mail.com', '333222111', 'Chicago', 'IL', '54321', '987654321']);
        $commandTester->execute([
            'id' => '00000000-0000-0000-0000-000000000000',
        ]);

        self::assertStringContainsString('User successfully updated', $commandTester->getDisplay());

        $file = $fileSystem->readFile($dbPath . '/users/00000000-0000-0000-0000-000000000000.json');

        self::assertEquals('{"id":"00000000-0000-0000-0000-000000000000","firstName":"Alice","lastName":"Cooper","birthdate":840585600,"email":"alice@mail.com","phoneNumber":"333222111","city":"Chicago","state":"IL","zip":"54321","ssn":"987654321","fico":300}', $file);
    }

    public function testNotexistingUser(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('user:update');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'id' => '00000000-0000-0000-0000-000000000001',
        ]);

        self::assertStringContainsString('User with id "00000000-0000-0000-0000-000000000001" not found', $commandTester->getDisplay());
    }
}