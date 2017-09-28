<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use LogicException;

/**
 * Class ConstructorParamTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel
 */
class ConstructorParamMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testGetPropertiesWithCustomParamName(): void
    {
        $paramName = 'test';
        $propertyName = 'testProperty';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $propertyType = $this->getMockBuilder(TypeInterface::class)->getMock();
        $propertyType->expects(self::once())
            ->method('isCompatibleWith')
            ->with($type)
            ->willReturn(true);

        $tuple = $this->getMockBuilder(Context\PropertyWithConstructorParamTupleInterface::class)->getMock();
        $tuple->expects(self::once())
            ->method('getPropertyType')
            ->willReturn($propertyType);
        $tuple->expects(self::once())
            ->method('getParamName')
            ->willReturn($paramName);
        $tuple->expects(self::once())
            ->method('getPropertyName')
            ->willReturn($propertyName);

        /* @var $tuple Context\PropertyWithConstructorParamTupleInterface */
        /* @var $type TypeInterface */
        $paramMetaData = new ConstructorParamMetaData($tuple, $type);

        self::assertSame($paramName, $paramMetaData->getName());
        self::assertSame($type, $paramMetaData->getType());
        self::assertSame($propertyName, $paramMetaData->getPropertyName());
    }

    /**
     * @expectedException LogicException
     */
    public function testGetPropertiesWithCustomParamNameThrowsLogicException(): void
    {
        $this->expectExceptionMessageRegExp(
            "/Constructor parameter '[a-zA-Z0-9]+' and property '[a-zA-Z0-9]+' have incompatible types./"
        );

        $paramName = 'test';
        $propertyName = 'testProperty';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $propertyType = $this->getMockBuilder(TypeInterface::class)->getMock();
        $propertyType->expects(self::once())
            ->method('isCompatibleWith')
            ->with($type)
            ->willReturn(false);

        $tuple = $this->getMockBuilder(Context\PropertyWithConstructorParamTupleInterface::class)->getMock();
        $tuple->expects(self::once())
            ->method('getPropertyType')
            ->willReturn($propertyType);
        $tuple->expects(self::once())
            ->method('getParamName')
            ->willReturn($paramName);
        $tuple->expects(self::once())
            ->method('getPropertyName')
            ->willReturn($propertyName);

        /* @var $tuple Context\PropertyWithConstructorParamTupleInterface */
        /* @var $type TypeInterface */
        new ConstructorParamMetaData($tuple, $type);
    }
}
