<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Performance\Serialization;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Door;
use BetterSerializer\Dto\Radio;
use Integration\AbstractIntegrationTest;
use JMS\Serializer\SerializerBuilder;

/**
 * Class Json
 * @author mfris
 * @package Integration\Serialization
 */
final class JsonTest extends AbstractIntegrationTest
{

    /**
     * @group performance
     * @group performanceSerialization
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

        $serializer = $this->getCachedSerializer();
        $jmsSerializer = $this->getJmsSerializer();

        $start = microtime(true);
        $serializer->serialize($data, SerializationType::JSON());
        $betterMicro = (microtime(true) - $start);

        $start = microtime(true);
        $jmsSerializer->serialize($data, 'json');
        $jmsMicro = (microtime(true) - $start);
        dump((float) $jmsMicro / (float) $betterMicro);

        self::assertGreaterThan($boost, (float) $jmsMicro / (float) $betterMicro);
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

        return [$car, 1.01];
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

        return [$data, 5.1];
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

        return [$data, 5.8];
    }
}
