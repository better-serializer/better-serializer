<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameter;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Car2;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\RadioInterface;
use BetterSerializer\Dto\SpecialCar;
use BetterSerializer\Dto\SpecialCarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ExtensionClassTypeTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $className = Car::class;
        $parameters = $this->createMock(ParametersInterface::class);

        $objExtension = new ExtensionClassType($className, $parameters);

        self::assertSame($className, $objExtension->getClassName());
        self::assertSame($className, $objExtension->getCustomType());
        self::assertSame($parameters, $objExtension->getParameters());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage This type must be used with classes or interfaces.
     */
    public function testThrowOnConstructionWithNoneExistentType(): void
    {
        $type = 'MyType';
        $parameters = $this->createMock(ParametersInterface::class);

        new ExtensionClassType($type, $parameters);
    }

    /**
     *
     */
    public function testBehaviour(): void
    {
        $className = Car::class;
        $parameters = $this->createMock(ParametersInterface::class);
        $objExtension = new ExtensionClassType($className, $parameters);

        $className2 = SpecialCar::class;
        $parameters2 = $this->createMock(ParametersInterface::class);
        $objExtension2 = new ExtensionClassType($className2, $parameters2);

        $interfaceName = SpecialCarInterface::class;
        $parameters3 = $this->createMock(ParametersInterface::class);
        $objExtension3 = new ExtensionClassType($interfaceName, $parameters3);

        $interfaceName2 = RadioInterface::class;
        $parameters4 = $this->createMock(ParametersInterface::class);
        $objExtension4 = new ExtensionClassType($interfaceName2, $parameters4);

        $class = Car::class;
        $objectType = new ClassType($class);

        $interface = CarInterface::class;
        $interfaceType = new InterfaceType($interface);

        self::assertTrue($objExtension->isClass());
        self::assertFalse($objExtension->isInterface());
        self::assertFalse($objExtension->extendsClass($objectType));
        self::assertFalse($objExtension->extendsClass($objExtension2));
        self::assertTrue($objExtension->implementsInterface($interfaceType));

        self::assertTrue($objExtension2->isClass());
        self::assertFalse($objExtension2->isInterface());
        self::assertTrue($objExtension2->extendsClass($objectType));
        self::assertTrue($objExtension2->extendsClass($objExtension));
        self::assertTrue($objExtension2->implementsInterface($interfaceType));

        self::assertFalse($objExtension3->isClass());
        self::assertTrue($objExtension3->isInterface());
        self::assertFalse($objExtension3->extendsClass($objectType));
        self::assertFalse($objExtension3->extendsClass($objExtension));
        self::assertTrue($objExtension3->implementsInterface($interfaceType));

        self::assertFalse($objExtension4->isClass());
        self::assertTrue($objExtension4->isInterface());
        self::assertFalse($objExtension4->extendsClass($objectType));
        self::assertFalse($objExtension4->extendsClass($objExtension));
        self::assertFalse($objExtension4->implementsInterface($interfaceType));
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ExtensionClassType(
            Car::class,
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

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
            [new ClassType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([new Parameter('name', 'value')])), true],
            [new ExtensionClassType(SpecialCar::class, new Parameters([])), false],
            [new ExtensionClassType(Car2::class, new Parameters([new Parameter('name', 'value')])), false],
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
        $type = new ExtensionClassType(
            Car::class,
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

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
            [new ClassType(Car2::class), false],
            [new InterfaceType(CarInterface::class), true],
            [new InterfaceType(SpecialCarInterface::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([])), true],
            [new ExtensionClassType(Car::class, new Parameters([new Parameter('name', 'value')])), true],
            [new ExtensionClassType(SpecialCar::class, new Parameters([])), true],
            [new ExtensionClassType(SpecialCarInterface::class, new Parameters([])), false],
            [new ExtensionClassType(Car2::class, new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        $type = new ExtensionClassType(
            Car::class,
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

        self::assertSame(
            sprintf('%s::%s(%s="%s")', TypeEnum::CUSTOM_CLASS_TYPE, Car::class, 'name', 'value'),
            (string) $type
        );
    }
}
