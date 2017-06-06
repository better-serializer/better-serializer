<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Performance\Serialization;

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Door;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\SerializerBuilder;

/**
 * Class Json
 * @author mfris
 * @package Integration\Serialization
 * @group performance
 */
final class JsonTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @dataProvider getTestTriples
     * @param mixed $data
     * @param float $boost
     */
    public function testSerialization($data, float $boost): void
    {
        if ($boost === 1.0) {
            $this->markTestIncomplete('Implement caching to see difference.');
        }

        $builder = new Builder();
        $serializer = $builder->createSerializer();
        $jmsSerializer = SerializerBuilder::create()->build();

        // opcache warmup
        $jmsSerializer->serialize($data, 'json');
        $serializer->writeValueAsString($data, SerializationType::JSON());

        $start = microtime(true);
        $serializer->writeValueAsString($data, SerializationType::JSON());
        $betterMicro = (microtime(true) - $start);

        $start = microtime(true);
        $jmsSerializer->serialize($data, 'json');
        $jmsMicro = (microtime(true) - $start);

        self::assertGreaterThan($boost, $jmsMicro / $betterMicro);
    }

    /**
     * @return array
     */
    public function getTestTriples(): array
    {
        return [
            $this->getSimpleObjectData(),
            $this->getBigArratData(),
            $this->getBigArrayWithNestedArray(),
        ];
    }

    /**
     * @return array
     */
    private function getSimpleObjectData(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);

        return [$car, 1.0];
    }

    /**
     * @return array
     */
    private function getBigArratData(): array
    {
        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);

        $data = [];

        for ($i = 0; $i < 2000; $i++) {
            $data[] = $car;
        }

        return [$data, 5.5];
    }

    /**
     * @return array
     */
    private function getBigArrayWithNestedArray(): array
    {
        $radio = new Radio('test station');
        $door = new Door();
        $doors = [$door, $door];
        $car = new Car('Honda', 'white', $radio, $doors);

        $data = [];

        for ($i = 0; $i < 2000; $i++) {
            $data[] = $car;
        }

        return [$data, 6.0];
    }
}
