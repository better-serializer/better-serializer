<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ArrayTypeTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $typeMock = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $typeMock TypeInterface */
        $type = new ArrayType($typeMock);
        self::assertInstanceOf(get_class(TypeEnum::ARRAY()), $type->getType());
        self::assertSame($typeMock, $type->getNestedType());
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProvider
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ArrayType(new StringType());

        self::assertSame($expectedResult, $type->equals($typeToTest));
    }

    /**
     * @return array
     */
    public function typeProvider(): array
    {
        return [
            [new ArrayType(new StringType()), true],
            [new ArrayType(new FloatType()), false],
            [new BooleanType(), false],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), false],
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        self::assertSame(
            TypeEnum::ARRAY . '<' . TypeEnum::STRING . '>',
            (string) new ArrayType(new StringType())
        );
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ArrayType(new StringType());

        self::assertSame($expectedResult, $type->isCompatibleWith($typeToTest));
    }

    /**
     * @return array
     */
    public function typeProviderForIsCompatible(): array
    {
        return [
            [new ArrayType(new StringType()), true],
            [new ArrayType(new BooleanType()), false],
            [new BooleanType(), false],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), false],
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
        ];
    }
}
