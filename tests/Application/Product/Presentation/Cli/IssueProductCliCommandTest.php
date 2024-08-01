<?php

declare(strict_types=1);

namespace App\Tests\Application\Product\Presentation\Cli;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class IssueProductCliCommandTest extends KernelTestCase
{
    private const USER_UUID = '00000000-0000-0000-0000-000000000000';

    private const PRODUCT_UUID = '00000000-0000-0000-0000-000000000000';

    private const POLICY_UUID = '00000000-0000-0000-0000-000000000000';

    public function testSuccess(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $dbPath = self::getContainer()->getParameter('db_path');
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile(
            filename: $dbPath . '/users/' . self::USER_UUID . '.json',
            content: '{"id":"' . self::USER_UUID . '","firstName":"John","lastName":"Doe","birthdate":639792000,"email":"mail@mail.com","phoneNumber":"111222333","city":"New York","state":"NY","zip":"12345","ssn":"123456789","fico":200}'
        );
        $fileSystem->dumpFile(
            filename: $dbPath . '/products/' . self::PRODUCT_UUID . '.json',
            content: '{"id":"' . self::PRODUCT_UUID . '","name":"Revolutionary Product","term":12,"interestRate":5,"amount":1000}'
        );
        $fileSystem->dumpFile(
            filename: $dbPath . '/policies/' . self::POLICY_UUID . '.json',
            content: '{"id":"' . self::POLICY_UUID . '","productId":"' . self::PRODUCT_UUID . '","field":"age","operator":"<=","amount":60,"stringValue":null,"arrayValues":[],"probability":100}',
        );

        $command = $application->find('product:issue');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'productId' => self::PRODUCT_UUID,
            'userId' => self::USER_UUID,
        ]);

        self::assertEquals(
            'Product issued successfully.' . PHP_EOL,
            $commandTester->getDisplay()
        );

        $fileContent = file_get_contents($dbPath . '/issued_products/00000000-0000-0000-0000-000000000000.json');
        self::assertEquals(
            '{"id":"00000000-0000-0000-0000-000000000000","productId":"00000000-0000-0000-0000-000000000000","issuedTo":"00000000-0000-0000-0000-000000000000","issuedAt":1293880271,"issuedUntil":1325416271,"interestRate":16.490000000000002,"amount":1000}',
            $fileContent
        );
    }

    public function testNotExistingUser(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('product:issue');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'productId' => self::PRODUCT_UUID,
            'userId' => '33333333-3333-3333-3333-333333333333',
        ]);

        self::assertEquals(
            'User with id "33333333-3333-3333-3333-333333333333" not found' . PHP_EOL,
            $commandTester->getDisplay()
        );
    }

    public function testNotExistingProduct(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('product:issue');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'productId' => '33333333-3333-3333-3333-333333333333',
            'userId' => self::USER_UUID,
        ]);

        self::assertEquals(
            'Product with id "33333333-3333-3333-3333-333333333333" not found' . PHP_EOL,
            $commandTester->getDisplay()
        );
    }

    public function testNotPossibleProduct(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $dbPath = self::getContainer()->getParameter('db_path');
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile(
            filename: $dbPath . '/users/' . self::USER_UUID . '.json',
            content: '{"id":"' . self::USER_UUID . '","firstName":"John","lastName":"Doe","birthdate":639792000,"email":"mail@mail.com","phoneNumber":"111222333","city":"New York","state":"NY","zip":"12345","ssn":"123456789","fico":200}'
        );
        $fileSystem->dumpFile(
            filename: $dbPath . '/products/' . self::PRODUCT_UUID . '.json',
            content: '{"id":"' . self::PRODUCT_UUID . '","name":"Revolutionary Product","term":12,"interestRate":5,"amount":1000}'
        );
        $fileSystem->dumpFile(
            filename: $dbPath . '/policies/' . self::POLICY_UUID . '.json',
            content: '{"id":"' . self::POLICY_UUID . '","productId":"' . self::PRODUCT_UUID . '","field":"age","operator":">","amount":60,"stringValue":null,"arrayValues":[],"probability":100}',
        );

        $command = $application->find('product:issue');
        $application->add($command);

        $helperSet = new HelperSet([
            'question' => new QuestionHelper(),
        ]);
        $application->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'productId' => self::PRODUCT_UUID,
            'userId' => self::USER_UUID,
        ]);

        self::assertEquals(
            'The product is not possible for the user.' . PHP_EOL,
            $commandTester->getDisplay()
        );
    }
}