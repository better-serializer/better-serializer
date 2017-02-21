<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectMapperTest
 * @author mfris
 * @package BetterSerializer\DataBind
 */
class SerializerTest extends TestCase
{

    /**
     *
     */
    public function testWriteValue(): void
    {
        //        $mapper = new Serializer(new Writer());
//        $return = $mapper->writeValueAsString(new Car('Volvo', 'red'), SerializationType::JSON());
//
//        self::assertEquals('test', $return);
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
