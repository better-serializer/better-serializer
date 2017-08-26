<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use PHPUnit\Framework\TestCase;

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
        $paramName = 'test';
        $propertyName = 'testProperty';

        $constructorParam = $this->createMock(ReflectionParameterInterface::class);
        $constructorParam->expects(self::once())
            ->method('getName')
            ->willReturn($paramName);

        $type = $this->createMock(TypeInterface::class);

        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn($propertyName);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $tuple = new PropertyWithConstructorParamTuple($constructorParam, $propertyMetaData);

        self::assertSame($constructorParam, $tuple->getConstructorParam());
        self::assertSame($propertyMetaData, $tuple->getPropertyMetaData());
        self::assertSame($paramName, $tuple->getParamName());
        self::assertSame($propertyName, $tuple->getPropertyName());
        self::assertSame($type, $tuple->getPropertyType());
    }
}
