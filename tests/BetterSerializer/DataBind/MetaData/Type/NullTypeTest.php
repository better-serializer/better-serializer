<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class NullTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class NullTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $null = new NullType();
        self::assertInstanceOf(get_class(TypeEnum::NULL()), $null->getType());
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProvider
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new NullType();

        self::assertSame($expectedResult, $type->equals($typeToTest));
    }

    /**
     * @return array
     */
    public function typeProvider(): array
    {
        return [
            [new ArrayType(new StringType()), false],
            [new BooleanType(), false],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), true],
            [new ObjectType(Car::class), false],
            [new StringType(), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        self::assertSame(TypeEnum::NULL, (string) new NullType());
    }
}
