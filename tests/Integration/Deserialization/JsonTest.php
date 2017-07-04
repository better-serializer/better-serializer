<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Deserialization;

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class Json
 * @author mfris
 * @package Integration\Deserialization
 */
final class JsonTest extends TestCase
{

    /**
     * @dataProvider getTestData
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $expectedJson
     * @param string $stringType
     */
    public function testDeserialization(string $expectedJson, string $stringType): void
    {
        $builder = new Builder();
        $serializer = $builder->createSerializer();

        $data = $serializer->deserialize($expectedJson, $stringType, SerializationType::JSON());
        $json = $serializer->serialize($data, SerializationType::JSON());

        self::assertSame($expectedJson, $json);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            $this->getNestedObjectTuple(),
            $this->getNestedObjectWithArrayTuple(),
            $this->getObjectsInArrayTuple(),
            $this->getObjectsInArrayTupleWithInnerArray(),
            $this->getStringsInArray(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedObjectTuple(): array
    {
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';

        return [$json, Car::class];
    }

    /**
     * @return array
     */
    private function getNestedObjectWithArrayTuple(): array
    {
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},'
            . '"doors":[{"parentalLock":false},{"parentalLock":false}]}';

        return [$json, Car::class];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTuple(): array
    {
        $jsonArray = [];

        for ($i = 0; $i < 2; $i++) {
            $jsonArray[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$json, 'array<' . Car::class .'>'];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTupleWithInnerArray(): array
    {
        $jsonArray = [];

        for ($i = 0; $i < 2; $i++) {
            $jsonArray[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"},'
                . '"doors":[{"parentalLock":false},{"parentalLock":false}]}';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$json, 'array<' . Car::class .'>'];
    }

    /**
     * @return array
     */
    private function getStringsInArray(): array
    {
        $jsonArray = [];

        for ($i = 0; $i < 2; $i++) {
            $jsonArray[] = '"test"';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$json, 'array<string>'];
    }
}
