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
     * @group integration
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSerialization(): void
    {
        $builder = new Builder();
        $serializer = $builder->createSerializer();

        $radio = new Radio('test station');
        $car = new Car('Honda', 'white', $radio);

        $json = $serializer->writeValueAsString($car, SerializationType::JSON());
        self::assertSame('{"title":"Honda","color":"white","radio":{"brand":"test station"}}', $json);
    }
}
