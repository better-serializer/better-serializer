<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;

/**
 * Class ConstructorParameterTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
class ConstructorParameterTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $reflectionParameter = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $reflectionParameter ReflectionParameter */
        /* @var $type TypeInterface */
        $constructorParameter = new ConstructorParameter($reflectionParameter, $type);

        self::assertSame($reflectionParameter, $constructorParameter->getReflectionParameter());
        self::assertSame($type, $constructorParameter->getType());
    }
}
