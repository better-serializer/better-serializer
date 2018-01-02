<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
use BetterSerializer\Dto\Car;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class UnknownTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UnknownTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $null = new UnknownType();
        self::assertInstanceOf(get_class(TypeEnum::UNKNOWN()), $null->getType());
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new UnknownType();

        self::assertSame($expectedResult, $type->equals($typeToTest));
    }

    /**
     * @return array
     */
    public function typeProviderForEquals(): array
    {
        return [
            [new ArrayType(new StringType()), false],
            [new BooleanType(), false],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), false],
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new UnknownType();

        self::assertSame($expectedResult, $type->isCompatibleWith($typeToTest));
    }

    /**
     * @return array
     */
    public function typeProviderForIsCompatible(): array
    {
        return [
            [new ArrayType(new StringType()), true],
            [new BooleanType(), true],
            [new FloatType(), true],
            [new IntegerType(), true],
            [new NullType(), true],
            [new ObjectType(Car::class), true],
            [new StringType(), true],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), true],
            [new ExtensionObjectType(Car::class, new Parameters([])), true],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), true],
        ];
    }
}
