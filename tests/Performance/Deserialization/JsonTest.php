<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Performance\Deserialization;

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\SerializerBuilder;

/**
 * Class Json
 * @author mfris
 * @package Integration\Deserialization
 */
final class JsonTest extends TestCase
{

    /**
     * @group performance
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @dataProvider getTestTriples
     * @param string $json
     * @param string $stringType
     * @param float $boost
     */
    public function testSerialization(string $json, string $stringType, float $boost): void
    {
        if ($boost === 1.0) {
            $this->markTestIncomplete('Implement caching to see difference.');
        }

        $builder = new Builder();
        $serializer = $builder->createSerializer();
        $jmsSerializer = SerializerBuilder::create()->build();

        // opcache warmup
        $jmsSerializer->deserialize($json, $stringType, 'json');
        $serializer->deserialize($json, $stringType, SerializationType::JSON());

        $start = microtime(true);
        $serializer->deserialize($json, $stringType, SerializationType::JSON());
        $betterMicro = (microtime(true) - $start);

        $start = microtime(true);
        $jmsSerializer->deserialize($json, $stringType, 'json');
        $jmsMicro = (microtime(true) - $start);
        //echo PHP_EOL . $jmsMicro . '-' . $betterMicro;

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

        return [$json, Car::class, 1.0];
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

        return [$json, 'array<' . Car::class .'>', 2.8];
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

        return [$json, 'array<' . Car::class .'>', 2.8];
    }
}
