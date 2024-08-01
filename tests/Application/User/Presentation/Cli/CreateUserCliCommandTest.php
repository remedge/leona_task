<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Presentation\Cli;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CreateUserCliCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('user:create');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['John', 'Doe', '11.04.1990', 'mail@mail.com', '111222333', 'New York', 'NY', '12345', '123456789']);
        $commandTester->execute([]);

        self::assertStringContainsString('User successfully created', $commandTester->getDisplay());

        $fileSystem = new Filesystem();

        $dbPath = self::getContainer()->getParameter('db_path');
        $file = $fileSystem->readFile($dbPath . '/users/00000000-0000-0000-0000-000000000000.json');

        self::assertEquals('{"id":"00000000-0000-0000-0000-000000000000","firstName":"John","lastName":"Doe","birthdate":639792000,"email":"mail@mail.com","phoneNumber":"111222333","city":"New York","state":"NY","zip":"12345","ssn":"123456789","fico":200}', $file);
    }
}