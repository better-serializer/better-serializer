<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Performance\Deserialization;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use Integration\AbstractIntegrationTest;
use JMS\Serializer\SerializerBuilder;

/**
 * Class Json
 * @author mfris
 * @package Integration\Deserialization
 */
final class JsonTest extends AbstractIntegrationTest
{

    /**
     * @group performance
     * @group performanceDeserialization
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @dataProvider getTestTriples
     * @param string $json
     * @param string $stringType
     * @param float $boost
     */
    public function testDeserialization(string $json, string $stringType, float $boost): void
    {
        if ($boost === 1.0) {
            $this->markTestIncomplete('Implement caching to see difference.');
        }

        $serializer = $this->getCachedSerializer();
        $jmsSerializer = $this->getJmsSerializer();

        $start = microtime(true);
        $serializer->deserialize($json, $stringType, SerializationType::JSON());
        $betterMicro = (microtime(true) - $start);

        $start = microtime(true);
        $jmsSerializer->deserialize($json, $stringType, 'json');
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
            $this->getSingleItem(),
            $this->getBigArray(),
            $this->getBigArrayWithNestedArray(),
        ];
    }

    /**
     * @return array
     */
    private function getSingleItem(): array
    {
        $json = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';

        return [$json, Car::class, 1.01];
    }

    /**
     * @return array
     */
    private function getBigArray(): array
    {
        $json = [];

        for ($i = 0; $i < 2000; $i++) {
            $json[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"},"doors":[]}';
        }

        $json = '[' . implode(',', $json) . ']';

        return [$json, 'array<' . Car::class .'>', 3.6];
    }

    /**
     * @return array
     */
    private function getBigArrayWithNestedArray(): array
    {
        $json = [];

        for ($i = 0; $i < 2000; $i++) {
            $json[] = '{"title":"Honda","color":"white","radio":{"brand":"test station"},'
                . '"doors":[{"parentalLock":false},{"parentalLock":false}]}';
        }

        $json = '[' . implode(',', $json) . ']';

        return [$json, 'array<' . Car::class .'>', 3.6];
    }
}
