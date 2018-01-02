<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\InterfaceType;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class NativeTypeFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class NativeTypeFactoryTest extends TestCase
{

    /**
     * @param string $stringType
     * @param string $expectedClass
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @dataProvider getTypeDataProvider
     */
    public function testGetType(string $stringType, string $expectedClass): void
    {
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringType);
        $stringFormType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $factory = new NativeTypeFactory();
        $type = $factory->getType($stringFormType);

        self::assertInstanceOf($expectedClass, $type);
    }

    /**
     * @return array
     */
    public function getTypeDataProvider(): array
    {
        return [
            ['string', StringType::class],
            ['bool', BooleanType::class],
            ['int', IntegerType::class],
            ['float', FloatType::class],
            ['', UnknownType::class],
        ];
    }

    /**
     *
     */
    public function testGetTypeObject(): void
    {
        $typeClass = TypeClassEnum::CLASS_TYPE();
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn(Car::class);
        $stringFormType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $factory = new NativeTypeFactory();
        /* @var $type ObjectType */
        $type = $factory->getType($stringFormType);

        self::assertInstanceOf(ObjectType::class, $type);
        self::assertSame(Car::class, $type->getClassName());
    }

    /**
     *
     */
    public function testGetTypeInterface(): void
    {
        $typeClass = TypeClassEnum::INTERFACE_TYPE();
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn(CarInterface::class);
        $stringFormType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $factory = new NativeTypeFactory();
        /* @var $type InterfaceType */
        $type = $factory->getType($stringFormType);

        self::assertInstanceOf(InterfaceType::class, $type);
        self::assertSame(CarInterface::class, $type->getInterfaceName());
    }

    /**
     *
     */
    public function testGetTypeArray(): void
    {
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn('array');
        $stringFormType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $factory = new NativeTypeFactory();
        /* @var $type ArrayType */
        $type = $factory->getType($stringFormType);

        self::assertInstanceOf(ArrayType::class, $type);
        self::assertInstanceOf(UnknownType::class, $type->getNestedType());
    }
}
