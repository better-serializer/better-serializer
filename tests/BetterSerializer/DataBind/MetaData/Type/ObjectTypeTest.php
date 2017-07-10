<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\SpecialCar;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class ObjectTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $object = new ObjectType(Car::class);
        self::assertInstanceOf(get_class(TypeEnum::OBJECT()), $object->getType());
        self::assertSame(Car::class, $object->getClassName());
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProvider
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ObjectType(Car::class);

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
            [new NullType(), false],
            [new ObjectType(Car::class), true],
            [new ObjectType(SpecialCar::class), false],
            [new StringType(), false],
        ];
    }
}
