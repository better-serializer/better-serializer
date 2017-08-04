<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;

/**
 * Class PropertyWithConstructorParamTupleTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context
 */
class PropertyWithConstructorParamTupleTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $constructorParam = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $classProperty = $this->getMockBuilder(PropertyMetaDataInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var $constructorParam ReflectionParameter */
        /* @var $classProperty PropertyMetaDataInterface */
        $tuple = new PropertyWithConstructorParamTuple($constructorParam, $classProperty);

        self::assertSame($constructorParam, $tuple->getConstructorParam());
        self::assertSame($classProperty, $tuple->getClassProperty());
    }
}
