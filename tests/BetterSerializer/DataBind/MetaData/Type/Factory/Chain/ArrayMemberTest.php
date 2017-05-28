<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

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
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     */
    public function testGetTypeWithSimpleSubType(): void
    {
        $stringType = 'array<string>';

        $stringTypeInstance = Mockery::mock(TypeInterface::class);

        $typeFactory = Mockery::mock(TypeFactoryInterface::class);
        $typeFactory->shouldReceive('getType')
            ->once()
            ->andReturn($stringTypeInstance);

        $context = Mockery::mock(StringTypedPropertyContextInterface::class);
        $context->shouldReceive('getStringType')
            ->once()
            ->andReturn($stringType)
            ->getMock()
            ->shouldReceive('getNamespace')
            ->once()
            ->andReturn('test')
            ->getMock();
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
        $objectTypeInstance = Mockery::mock(TypeInterface::class);

        $typeFactory = Mockery::mock(TypeFactoryInterface::class);
        $typeFactory->shouldReceive('getType')
            ->once()
            ->andReturn($objectTypeInstance);

        $context = Mockery::mock(StringTypedPropertyContextInterface::class);
        $context->shouldReceive('getStringType')
            ->once()
            ->andReturn($stringType)
            ->getMock()
            ->shouldReceive('getNamespace')
            ->once()
            ->andReturn('test')
            ->getMock();
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
        $typeFactory = Mockery::mock(TypeFactoryInterface::class);

        $context = Mockery::mock(StringTypedPropertyContextInterface::class);
        $context->shouldReceive('getStringType')
            ->once()
            ->andReturn(TypeEnum::STRING)
            ->getMock();
        /* @var $context StringTypedPropertyContextInterface */

        $arrayMember = new ArrayMember($typeFactory);
        $shouldBeNull = $arrayMember->getType($context);

        self::assertNull($shouldBeNull);
    }
}
