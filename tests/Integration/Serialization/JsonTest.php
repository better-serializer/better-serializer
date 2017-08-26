<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration\Serialization;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Car2;
use BetterSerializer\Dto\Door;
use BetterSerializer\Dto\Nested\CarFactory;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\SpecialCar;
use Integration\AbstractIntegrationTest;
use DateTime;
use DateTimeImmutable;

/**
 * Class Json
 * @author mfris
 * @package Integration\Serialization
 */
final class JsonTest extends AbstractIntegrationTest
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
        $serializer = $this->getSerializer();

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
            $this->getOverridenNameTuple(),
            $this->getNamespaceFeatureTupleWithDateTimes(),
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

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getOverridenNameTuple(): array
    {
        $car2 = new Car2(
            'testTitle',
            DateTime::createFromFormat('Y-m-d H:i:s', '2010-09-01 08:07:06'),
            DateTime::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00'),
            DateTimeImmutable::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00')
        );
        $json = '{"serializedTitle":"testTitle","manufactured":"2010-09-01 08:07:06",'
            . '"selled":"2017-08-19T17:31:09+00:00","serviced":"2017-08-19T17:31:09+00:00","dismantled":null}';

        return [$car2, $json];
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getNamespaceFeatureTupleWithDateTimes(): array
    {
        $radio = new Radio('test station');
        $car = new SpecialCar('Honda', 'white', $radio, 'special');
        $carJson = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[],"special":"special"}';

        $car2 = new Car2(
            'testTitle',
            DateTime::createFromFormat('Y-m-d H:i:s', '2010-09-01 08:07:06'),
            DateTime::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00'),
            DateTimeImmutable::createFromFormat(DateTime::ATOM, '2017-08-19T17:31:09+00:00')
        );
        $car2Json = '{"serializedTitle":"testTitle","manufactured":"2010-09-01 08:07:06",'
            . '"selled":"2017-08-19T17:31:09+00:00","serviced":"2017-08-19T17:31:09+00:00","dismantled":null}';

        $factory = new CarFactory([$car], [$car2]);
        $json = '{"cars":[' . $carJson .'],"cars2":['. $car2Json .']}';

        return [$factory, $json];
    }
}
