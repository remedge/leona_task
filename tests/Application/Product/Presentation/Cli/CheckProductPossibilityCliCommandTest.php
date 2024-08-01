<?php

declare(strict_types=1);

namespace App\Tests\Application\Product\Presentation\Cli;

use Generator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CheckProductPossibilityCliCommandTest extends KernelTestCase
{
    private const USER_UUID = '00000000-0000-0000-0000-000000000000';

    private const PRODUCT_UUID = '00000000-0000-0000-0000-000000000000';

    private const POLICY_UUID = '00000000-0000-0000-0000-000000000000';

    /**
     * @dataProvider providePolicy
     */
    public function testExecute(string $policy, string $result): void
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
            content: $policy,
        );

        $command = $application->find('product:check');
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
            $result,
            $commandTester->getDisplay()
        );
    }

    public function providePolicy(): Generator
    {
        yield [
            'policy' => '{"id":"' . self::POLICY_UUID . '","productId":"' . self::PRODUCT_UUID . '","field":"age","operator":"<=","amount":60,"stringValue":null,"arrayValues":[],"probability":100}',
            'result' => 'The product is possible for the user.' . PHP_EOL . 'Interest rate: 16.49' . PHP_EOL,
        ];
        yield [
            'policy' => '{"id":"' . self::POLICY_UUID . '","productId":"' . self::PRODUCT_UUID . '","field":"age","operator":">","amount":60,"stringValue":null,"arrayValues":[],"probability":100}',
            'result' => 'The product is not possible for the user.' . PHP_EOL,
        ];
    }
}