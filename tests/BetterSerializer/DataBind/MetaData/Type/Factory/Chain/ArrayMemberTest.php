<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ArrayMemberTest extends TestCase
{

    /**
     *
     */
    public function testGetTypeWithSimpleStringSubType(): void
    {
        $stringType = TypeEnum::ARRAY_TYPE;
        $stringTypeInstance = $this->createMock(TypeInterface::class);
        $nestedStringFormType = $this->createMock(ContextStringFormTypeInterface::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->with($nestedStringFormType)
            ->willReturn($stringTypeInstance);

        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringType);
        $stringFormType->expects(self::exactly(2))
            ->method('getCollectionValueType')
            ->willReturn($nestedStringFormType);

        $arrayMember = new ArrayMember($typeFactory);
        /* @var $arrayType ArrayType */
        $arrayType = $arrayMember->getType($stringFormType);

        self::assertNotNull($arrayType);
        self::assertInstanceOf(ArrayType::class, $arrayType);
        self::assertSame($arrayType->getNestedType(), $stringTypeInstance);
    }

    /**
     *
     */
    public function testGetTypeReturnsNullWhenNotArray(): void
    {
        $typeFactory = $this->createMock(TypeFactoryInterface::class);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn(TypeEnum::STRING_TYPE);

        $arrayMember = new ArrayMember($typeFactory);
        $shouldBeNull = $arrayMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }

    /**
     *
     */
    public function testGetTypeReturnsNullWhenColValueTypeMissing(): void
    {
        $typeFactory = $this->createMock(TypeFactoryInterface::class);

        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn(TypeEnum::ARRAY_TYPE);
        $stringFormType->expects(self::once())
            ->method('getCollectionValueType')
            ->willReturn(null);

        $arrayMember = new ArrayMember($typeFactory);
        $shouldBeNull = $arrayMember->getType($stringFormType);

        self::assertNull($shouldBeNull);
    }
}
