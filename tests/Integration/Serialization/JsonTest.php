<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization;

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Door;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\SpecialCar;
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

        $json = $serializer->serialize($data, SerializationType::JSON());
        self::assertSame($expectedJson, $json);
    }

    /**
     * @return array
     */
    public function getTestTuples(): array
    {
        return [
            $this->getNestedObjectTuple(),
            $this->getNestedObjectTupleAndArray(),
            $this->getObjectsInArrayTuple(),
            $this->getObjectsInArrayTupleWithInnerArray(),
            $this->getStringsInArray(),
            $this->getInheritedObjectTuple(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedObjectTuple(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';

        return [$car, $json];
    }

    /**
     * @return array
     */
    private function getNestedObjectTupleAndArray(): array
    {
        $radio = new Radio('test station');
        $door = new Door();
        $doors = [$door, $door];
        $car = new Car('Honda', 'white', $radio, $doors);
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},'
            . '"doors":[{"parentalLock":false},{"parentalLock":false}]}';

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
            $jsonArray[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$cars, $json];
    }

    /**
     * @return array
     */
    private function getObjectsInArrayTupleWithInnerArray(): array
    {
        $radio = new Radio('test station');
        $door = new Door();
        $doors = [$door, $door];
        $car = new Car('Honda', 'white', $radio, $doors);
        $cars = [];
        $jsonArray = [];

        for ($i = 0; $i < 2; $i++) {
            $cars[] = $car;
            $jsonArray[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"},'
                . '"doors":[{"parentalLock":false},{"parentalLock":false}]}';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$cars, $json];
    }

    /**
     * @return array
     */
    private function getStringsInArray(): array
    {
        $string = 'test';
        $strings = [];
        $jsonArray = [];

        for ($i = 0; $i < 2; $i++) {
            $strings[] = $string;
            $jsonArray[] = '"test"';
        }

        $json = '[' . implode(',', $jsonArray) . ']';

        return [$strings, $json];
    }

    /**
     * @return array
     */
    private function getInheritedObjectTuple(): array
    {
        $radio = new Radio('test station');
        $car = new SpecialCar('Honda', 'white', $radio, 'special');
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[],"special":"special"}';

        return [$car, $json];
    }
}
