<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ArrayMemberTest extends TestCase
{

    /**
     *
     */
    public function testGetTypeWithSimpleSubType(): void
    {
        $stringType = 'array<string>';

        $stringTypeInstance = $this->getMockBuilder(TypeInterface::class)->getMock();

        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($stringTypeInstance);
        /* @var $typeFactory TypeFactoryInterface */

        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringType);
        $context->expects(self::once())
            ->method('getNamespace')
            ->willReturn('test');
        /* @var $context StringTypedPropertyContextInterface */

        $arrayMember = new ArrayMember($typeFactory);
        /* @var $arrayType ArrayType */
        $arrayType = $arrayMember->getType($context);

        self::assertInstanceOf(ArrayType::class, $arrayType);
        self::assertSame($arrayType->getNestedType(), $stringTypeInstance);
    }

    /**
     *
     */
    public function testGetTypeWithObjectSubType(): void
    {
        $stringType = 'array<Car>';
        $objectTypeInstance = $this->getMockBuilder(TypeInterface::class)->getMock();

        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($objectTypeInstance);
        /* @var $typeFactory TypeFactoryInterface */

        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringType);
        $context->expects(self::once())
            ->method('getNamespace')
            ->willReturn('test');
        /* @var $context StringTypedPropertyContextInterface */

        $arrayMember = new ArrayMember($typeFactory);
        /* @var $arrayType ArrayType */
        $arrayType = $arrayMember->getType($context);

        self::assertInstanceOf(ArrayType::class, $arrayType);
        self::assertSame($arrayType->getNestedType(), $objectTypeInstance);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        /* @var $typeFactory TypeFactoryInterface */

        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getStringType')
            ->willReturn(TypeEnum::STRING);
        /* @var $context StringTypedPropertyContextInterface */

        $arrayMember = new ArrayMember($typeFactory);
        $shouldBeNull = $arrayMember->getType($context);

        self::assertNull($shouldBeNull);
    }
}
