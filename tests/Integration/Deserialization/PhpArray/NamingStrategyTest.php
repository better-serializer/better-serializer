<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Deserialization\PhpArray;

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
     * @param NamingStrategy $namingStrategy
     * @param mixed $expectedData
     * @param string $stringType
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDeserialization(NamingStrategy $namingStrategy, $expectedData, string $stringType): void
    {
        $serializer = $this->getSerializer([self::NAMING_STRATEGY => $namingStrategy], true);

        $data = $serializer->deserialize($expectedData, $stringType, SerializationType::PHP_ARRAY());
        $json = $serializer->serialize($data, SerializationType::PHP_ARRAY());

        self::assertSame($expectedData, $json);
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
        $data = [
            ['parentalLock' => false],
            ['parentalLock' => true],
        ];

        return [NamingStrategy::IDENTITY(), $data, 'array<' . Door::class .'>'];
    }

    /**
     * @return array
     */
    private function getSnakeCaseToCamelCaseArray(): array
    {
        $data = [
            ['parentalLock' => false],
            ['parentalLock' => true],
        ];

        return [NamingStrategy::CAMEL_CASE(), $data, 'array<' . DoorSnakeCase::class .'>'];
    }

    /**
     * @return array
     */
    private function getCamelCaseToSnakeCaseArray(): array
    {
        $data = [
            ['parental_lock' => false],
            ['parental_lock' => true],
        ];

        return [NamingStrategy::SNAKE_CASE(), $data, 'array<' . Door::class .'>'];
    }
}
