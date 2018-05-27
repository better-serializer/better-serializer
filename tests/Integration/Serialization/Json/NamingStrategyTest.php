<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization\Json;

use BetterSerializer\Common\NamingStrategy;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Door;
use BetterSerializer\Dto\DoorSnakeCase;
use Integration\AbstractIntegrationTest;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class NamingStrategyTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @param NamingStrategy $namingStrategy
     * @param mixed $data
     * @param string $expectedJson
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSerialization(NamingStrategy $namingStrategy, $data, string $expectedJson): void
    {
        $serializer = $this->getSerializer([self::NAMING_STRATEGY => $namingStrategy], true);

        $json = $serializer->serialize($data, SerializationType::JSON());
        self::assertSame($expectedJson, $json);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTestTuples(): array
    {
        return [
            $this->getIdentityArray(),
            $this->getSnakeCaseToCamelCaseArray(),
            $this->getCamelCaseToSnakeCaseArray(),
        ];
    }

    /**
     * @return array
     */
    private function getIdentityArray(): array
    {
        $doors = [new Door(), new Door(true)];
        $data = [
            ['parentalLock' => false],
            ['parentalLock' => true],
        ];

        $json = json_encode($data);

        return [NamingStrategy::IDENTITY(), $doors, $json];
    }

    /**
     * @return array
     */
    private function getSnakeCaseToCamelCaseArray(): array
    {
        $doors = [new DoorSnakeCase(), new DoorSnakeCase(true)];
        $data = [
            ['parentalLock' => false],
            ['parentalLock' => true],
        ];

        $json = json_encode($data);

        return [NamingStrategy::CAMEL_CASE(), $doors, $json];
    }

    /**
     * @return array
     */
    private function getCamelCaseToSnakeCaseArray(): array
    {
        $doors = [new Door(), new Door(true)];
        $data = [
            ['parental_lock' => false],
            ['parental_lock' => true],
        ];

        $json = json_encode($data);

        return [NamingStrategy::SNAKE_CASE(), $doors, $json];
    }
}
