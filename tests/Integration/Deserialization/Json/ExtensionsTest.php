<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Deserialization\Json;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car3;
use Integration\AbstractIntegrationTest;

/**
 * Class Json
 * @author mfris
 * @package Integration\Deserialization
 */
final class ExtensionsTest extends AbstractIntegrationTest
{

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $expectedJson
     * @param string $stringType
     * @param string $testMethod
     * @param string $testResult
     * @throws \LogicException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testDeserialization(string $expectedJson, string $stringType, string $testMethod, $testResult): void
    {
        $serializer = $this->getSerializer();

        $data = $serializer->deserialize($expectedJson, $stringType, SerializationType::JSON());
        $json = $serializer->serialize($data, SerializationType::JSON());

        self::assertSame($expectedJson, $json);

        $result = $data->{$testMethod}();

        self::assertSame($testResult, $result);
    }

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $expectedJson
     * @param string $stringType
     * @param string $testMethod
     * @param string $testResult
     * @throws \LogicException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testDeserializationCached(
        string $expectedJson,
        string $stringType,
        string $testMethod,
        $testResult
    ): void {
        $serializer = $this->getCachedSerializer();

        $data = $serializer->deserialize($expectedJson, $stringType, SerializationType::JSON());
        $json = $serializer->serialize($data, SerializationType::JSON());

        self::assertSame($expectedJson, $json);

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
        $json = '{"doors":[{"parentalLock":false},{"parentalLock":true}],"isForKids":"yes"}';

        return [$json, Car3::class, 'isForKids', true];
    }
}
