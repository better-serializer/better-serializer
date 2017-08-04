<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class NativeTypeFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory
 */
class NativeTypeFactoryTest extends TestCase
{

    /**
     * @param string $stringType
     * @param string $expectedClass
     * @dataProvider getTypeDataProvider
     */
    public function testGetType(string $stringType, string $expectedClass): void
    {
        $stringFormType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringType);

        /* @var $stringFormType StringFormTypeInterface */
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
        $stringFormType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn('Car');
        $stringFormType->expects(self::once())
            ->method('getNamespace')
            ->willReturn('BetterSerializer\\Dto');
        $stringFormType->expects(self::once())
            ->method('isClass')
            ->willReturn(true);

        $factory = new NativeTypeFactory();
        /* @var $stringFormType StringFormTypeInterface */
        /* @var $type ObjectType */
        $type = $factory->getType($stringFormType);

        self::assertInstanceOf(ObjectType::class, $type);
        self::assertSame(Car::class, $type->getClassName());
    }

    /**
     *
     */
    public function testGetTypeArray(): void
    {
        $stringFormType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn('array');

        $factory = new NativeTypeFactory();
        /* @var $stringFormType StringFormTypeInterface */
        /* @var $type ArrayType */
        $type = $factory->getType($stringFormType);

        self::assertInstanceOf(ArrayType::class, $type);
        self::assertInstanceOf(UnknownType::class, $type->getNestedType());
    }
}
