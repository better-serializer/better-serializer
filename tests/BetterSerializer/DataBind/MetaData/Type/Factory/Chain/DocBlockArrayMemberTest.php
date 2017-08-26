<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class DocBlockArrayMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
class DocBlockArrayMemberTest extends TestCase
{

    /**
     *
     */
    public function testGetTypeWithSimpleStringType(): void
    {
        $stringTypeString = 'string[]';

        $stringTypeInstance = $this->createMock(TypeInterface::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($stringTypeInstance);

        $stringType = $this->createMock(StringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringTypeString);

        $arrayMember = new DocBlockArrayMember($typeFactory);
        /* @var $arrayType ArrayType */
        $arrayType = $arrayMember->getType($stringType);

        self::assertInstanceOf(ArrayType::class, $arrayType);
        self::assertSame($arrayType->getNestedType(), $stringTypeInstance);
    }

    /**
     *
     */
    public function testGetTypeWithContextStringType(): void
    {
        $stringTypeString = 'Car[]';
        $objectTypeInstance = $this->createMock(TypeInterface::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($objectTypeInstance);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringTypeString);

        $arrayMember = new DocBlockArrayMember($typeFactory);
        /* @var $arrayType ArrayType */
        $arrayType = $arrayMember->getType($stringType);

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

        $stringType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn(TypeEnum::STRING);
        /* @var $stringType StringFormTypeInterface */

        $arrayMember = new DocBlockArrayMember($typeFactory);
        $shouldBeNull = $arrayMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }
}
