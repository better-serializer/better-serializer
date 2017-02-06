<?php
/**
 * @author  mfris
 */

namespace BetterSerializer\DataBind;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectMapperTest
 * @author mfris
 * @package BetterSerializer\DataBind
 */
class ObjectMapperTest extends TestCase
{

    public function testWriteValue(): void
    {
        $mapper = new ObjectMapper(new Writer());
        $return = $mapper->writeValueAsString(new Car('Volvo', 'red'), SerializationType::JSON());

        $this->assertEquals('test', $return);
    }
}
