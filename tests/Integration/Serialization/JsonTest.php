<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization;

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

/**
 * Class Json
 * @author mfris
 * @package Integration\Serialization
 */
final class JsonTest extends TestCase
{

    /**
     * @dataProvider getTestTuples
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $data
     * @param string $expectedJson
     */
    public function testSerialization($data, string $expectedJson): void
    {
        $builder = new Builder();
        $serializer = $builder->createSerializer();

        $json = $serializer->writeValueAsString($data, SerializationType::JSON());
        self::assertSame($expectedJson, $json);
    }

    /**
     * @return array
     */
    public function getTestTuples(): array
    {
        return [
            $this->getNestedObjectTuple(),
            $this->getObjectsInArrayTuple(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedObjectTuple(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"}}';

        return [$car, $json];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTuple(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $cars = [];
        $jsonArray = [];

        for ($i = 0; $i < 2; $i++) {
            $cars[] = $car;
            $jsonArray[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"}}';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$cars, $json];
    }
}
