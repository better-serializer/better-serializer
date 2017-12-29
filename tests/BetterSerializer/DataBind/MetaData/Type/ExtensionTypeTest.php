<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\Parameter;
use BetterSerializer\DataBind\MetaData\Type\Parameters\Parameters;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ExtensionTypeTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = 'MyType';
        $parameters = $this->createMock(ParametersInterface::class);

        $extension = new ExtensionType($type, $parameters);

        self::assertSame($type, $extension->getCustomType());
        self::assertSame($parameters, $extension->getParameters());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage This type shouldn't be used with classes or interfaces.
     */
    public function testThrowOnConstructionWithClass(): void
    {
        $type = Car::class;
        $parameters = $this->createMock(ParametersInterface::class);

        new ExtensionType($type, $parameters);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage This type shouldn't be used with classes or interfaces.
     */
    public function testThrowOnConstructionWithInterface(): void
    {
        $type = CarInterface::class;
        $parameters = $this->createMock(ParametersInterface::class);

        new ExtensionType($type, $parameters);
    }

    /**
     *
     */
    public function testBehaviour(): void
    {
        $type1 = 'MyType';
        $parameters1 = $this->createMock(ParametersInterface::class);
        $extension1 = new ExtensionType($type1, $parameters1);

        $class = Car::class;
        $objectType = new ObjectType($class);

        $interface = CarInterface::class;
        $interfaceType = new InterfaceType($interface);

        self::assertFalse($extension1->isClass());
        self::assertFalse($extension1->isInterface());
        self::assertFalse($extension1->extendsClass($objectType));
        self::assertFalse($extension1->implementsInterface($interfaceType));
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ExtensionType(
            'MyType',
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
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionType('MyType', new Parameters([new Parameter('name', 'value')])), true],
            [new ExtensionType('MyType1', new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ExtensionType(
            'MyType',
            new Parameters([
                new Parameter('name', 'value'),
            ]),
            new BooleanType()
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
            [new BooleanType(), true],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), false],
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionType('MyType', new Parameters([new Parameter('name', 'value')])), true],
            [new ExtensionType('MyType1', new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        $type = new ExtensionType(
            'MyType',
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

        self::assertSame(
            sprintf('%s::%s(%s="%s")', TypeEnum::CUSTOM_TYPE, 'MyType', 'name', 'value'),
            (string) $type
        );
    }
}
