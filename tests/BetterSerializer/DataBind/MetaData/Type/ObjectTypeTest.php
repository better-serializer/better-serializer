<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\Parameters;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\SpecialCar;
use BetterSerializer\Dto\SpecialCarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
     *
     */
    public function testBehaviour(): void
    {
        $object = new ObjectType(Car::class);
        $interface = new InterfaceType(SpecialCarInterface::class);
        $object2 = new ObjectType(SpecialCar::class);

        self::assertFalse($object->extendsClass($object2));
        self::assertFalse($object->extendsClassAsString(SpecialCar::class));
        self::assertFalse($object->implementsInterface($interface));
        self::assertTrue($object2->extendsClass($object));
        self::assertTrue($object2->extendsClassAsString(Car::class));
        self::assertTrue($object2->implementsInterface($interface));
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ObjectType(Car::class);

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
            [new ObjectType(Car::class), true],
            [new ObjectType(SpecialCar::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        self::assertSame(
            TypeEnum::OBJECT . '<' . Car::class . '>',
            (string) new ObjectType(Car::class)
        );
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ObjectType(Car::class);

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
            [new ExtensionObjectType(Car::class, new Parameters([])), true],
            [new ExtensionObjectType(CarInterface::class, new Parameters([])), true],
            [new ExtensionObjectType(Radio::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
            [new ExtensionCollectionType(Car::class, new StringType(), new Parameters([])), true],
            [new InterfaceType(CarInterface::class), true],
            [new InterfaceType(SpecialCarInterface::class), false],
        ];
    }
}
