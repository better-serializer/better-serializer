<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Deserialization\Json;

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
     * @param string $expectedJson
     * @param string $stringType
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDeserialization(NamingStrategy $namingStrategy, string $expectedJson, string $stringType): void
    {
        $serializer = $this->getSerializer([self::NAMING_STRATEGY => $namingStrategy], true);

        $data = $serializer->deserialize($expectedJson, $stringType, SerializationType::JSON());
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
        $data = [
            ['parentalLock' => false],
            ['parentalLock' => true],
        ];

        $json = json_encode($data);

        return [NamingStrategy::IDENTITY(), $json, 'array<' . Door::class .'>'];
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

        $json = json_encode($data);

        return [NamingStrategy::CAMEL_CASE(), $json, 'array<' . DoorSnakeCase::class .'>'];
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

        $json = json_encode($data);

        return [NamingStrategy::SNAKE_CASE(), $json, 'array<' . Door::class .'>'];
    }
}
