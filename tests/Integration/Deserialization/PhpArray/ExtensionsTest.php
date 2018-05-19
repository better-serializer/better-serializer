<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Deserialization\PhpArray;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car3;
use Integration\AbstractIntegrationTest;

/**
 *
 */
final class ExtensionsTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $expectedData
     * @param string $stringType
     * @param string $testMethod
     * @param string $testResult
     * @throws \LogicException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testDeserialization($expectedData, string $stringType, string $testMethod, $testResult): void
    {
        $serializer = $this->getSerializer();

        $data = $serializer->deserialize($expectedData, $stringType, SerializationType::PHP_ARRAY());
        $serialized = $serializer->serialize($data, SerializationType::PHP_ARRAY());

        self::assertSame($expectedData, $serialized);

        $result = $data->{$testMethod}();

        self::assertSame($testResult, $result);
    }

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $expectedData
     * @param string $stringType
     * @param string $testMethod
     * @param string $testResult
     * @throws \LogicException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testDeserializationCached(
        $expectedData,
        string $stringType,
        string $testMethod,
        $testResult
    ): void {
        $serializer = $this->getCachedSerializer();

        $data = $serializer->deserialize($expectedData, $stringType, SerializationType::PHP_ARRAY());
        $serialized = $serializer->serialize($data, SerializationType::PHP_ARRAY());

        self::assertSame($expectedData, $serialized);

        $result = $data->{$testMethod}();

        self::assertSame($testResult, $result);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            $this->getCustomExtensionTuple(),
        ];
    }

    /**
     * @return array
     */
    private function getCustomExtensionTuple(): array
    {
        $data = [
            'doors' => [
                [
                    'parentalLock' => false
                ],
                [
                    'parentalLock' => true
                ]
            ],
            'isForKids' => 'yes'
        ];

        return [$data, Car3::class, 'isForKids', true];
    }
}
