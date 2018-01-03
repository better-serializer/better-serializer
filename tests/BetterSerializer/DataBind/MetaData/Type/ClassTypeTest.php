<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\SpecialCar;
use BetterSerializer\Dto\SpecialCarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ClassTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $object = new ClassType(Car::class);
        self::assertInstanceOf(get_class(TypeEnum::CLASS_TYPE()), $object->getType());
        self::assertSame(Car::class, $object->getClassName());
    }

    /**
     *
     */
    public function testBehaviour(): void
    {
        $object = new ClassType(Car::class);
        $interface = new InterfaceType(SpecialCarInterface::class);
        $object2 = new ClassType(SpecialCar::class);

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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ClassType(Car::class);

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
            [new ClassType(Car::class), true],
            [new ClassType(SpecialCar::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        self::assertSame(
            TypeEnum::CLASS_TYPE . '<' . Car::class . '>',
            (string) new ClassType(Car::class)
        );
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ClassType(Car::class);

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
            [new ClassType(Car::class), true],
            [new ClassType(SpecialCar::class), true],
            [new ClassType(Radio::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([])), true],
            [new ExtensionClassType(CarInterface::class, new Parameters([])), true],
            [new ExtensionClassType(Radio::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
            [new ExtensionCollectionType(Car::class, new StringType(), new Parameters([])), true],
            [new InterfaceType(CarInterface::class), true],
            [new InterfaceType(SpecialCarInterface::class), false],
        ];
    }
}
