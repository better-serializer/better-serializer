<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Performance\Serialization;

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\SerializerBuilder;

/**
 * Class Json
 * @author mfris
 * @package Integration\Serialization
 */
final class JsonTest extends TestCase
{

    /**
     * @group performance
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSerialization(): void
    {
        $this->markTestIncomplete();
        $expectedJson = '{"title":"Honda","color":"white","radio":{"brand":"test station"}}';

        $builder = new Builder();
        $serializer = $builder->createSerializer();
        $jmsSerializer = SerializerBuilder::create()->build();

        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);

        // opcache warmup
        $jmsSerializer->serialize($car, 'json');
        $serializer->writeValueAsString($car, SerializationType::JSON());

        //$start = microtime(true);
        $json = $serializer->writeValueAsString($car, SerializationType::JSON());
        //echo (microtime(true) - $start) . ' yyy ';
        self::assertSame($expectedJson, $json);

        //$start = microtime(true);
        $jmsJson = $jmsSerializer->serialize($car, 'json');
        //echo (microtime(true) - $start) . ' xxx ';
        self::assertSame($expectedJson, $jmsJson);
    }
}
