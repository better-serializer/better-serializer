<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\Parameters;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\RadioInterface;
use BetterSerializer\Dto\SpecialCar;
use BetterSerializer\Dto\SpecialCarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InterfaceTypeTest extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $interface = new InterfaceType(CarInterface::class);
        self::assertInstanceOf(get_class(TypeEnum::INTERFACE()), $interface->getType());
        self::assertSame(CarInterface::class, $interface->getInterfaceName());
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testBehaviour(): void
    {
        $interface = new InterfaceType(CarInterface::class);
        $interface2 = new InterfaceType(SpecialCarInterface::class);

        self::assertFalse($interface->implementsInterface($interface2));
        self::assertFalse($interface->implementsInterfaceAsString(SpecialCarInterface::class));
        self::assertTrue($interface2->implementsInterface($interface));
        self::assertTrue($interface2->implementsInterfaceAsString(CarInterface::class));
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new InterfaceType(CarInterface::class);

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
            [new ObjectType(SpecialCar::class), false],
            [new ObjectType(Radio::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
            [new InterfaceType(CarInterface::class), true],
            [new InterfaceType(SpecialCarInterface::class), false],
            [new InterfaceType(RadioInterface::class), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        self::assertSame(
            TypeEnum::INTERFACE . '<' . CarInterface::class . '>',
            (string) new InterfaceType(CarInterface::class)
        );
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new InterfaceType(CarInterface::class);

        self::assertSame($expectedResult, $type->isCompatibleWith($typeToTest));
    }

    /**
     * @return array
     */
    public function typeProviderForIsCompatible(): array
    {
        return [
            [new ArrayType(new StringType()), false],
            [new BooleanType(), false],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), false],
            [new ObjectType(Car::class), true],
            [new ObjectType(SpecialCar::class), true],
            [new ObjectType(Radio::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionObjectType(Radio::class, new Parameters([])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), true],
            [new ExtensionObjectType(SpecialCar::class, new Parameters([])), true],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
            [new ExtensionCollectionType(CarInterface::class, new StringType(), new Parameters([])), true],
            [new ExtensionCollectionType(SpecialCarInterface::class, new StringType(), new Parameters([])), true],
            [new InterfaceType(CarInterface::class), true],
            [new InterfaceType(SpecialCarInterface::class), true],
            [new InterfaceType(RadioInterface::class), false],
        ];
    }
}
