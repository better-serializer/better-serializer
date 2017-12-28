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
use BetterSerializer\Dto\DerivedArrayCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ExtensionCollectionTypeTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = Collection::class;
        $nestedType = $this->createMock(ExtensionTypeInterface::class);
        $parameters = $this->createMock(ParametersInterface::class);

        $collectionType = new ExtensionCollectionType($type, $nestedType, $parameters);

        self::assertSame($type, $collectionType->getCustomType());
        self::assertSame($nestedType, $collectionType->getNestedType());
        self::assertSame($parameters, $collectionType->getParameters());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage This type must be used with classes or interfaces.
     */
    public function testThrowOnConstructionWithNoneExistentType(): void
    {
        $type = 'MyType';
        $nestedType = $this->createMock(ExtensionTypeInterface::class);
        $parameters = $this->createMock(ParametersInterface::class);

        new ExtensionCollectionType($type, $nestedType, $parameters);
    }

    /**
     *
     */
    public function testBehaviour(): void
    {
        $nestedType = $this->createMock(ExtensionTypeInterface::class);

        $type1 = DerivedArrayCollection::class;
        $parameters = $this->createMock(ParametersInterface::class);
        $objExtension = new ExtensionCollectionType($type1, $nestedType, $parameters);

        $type2 = Collection::class;
        $parameters2 = $this->createMock(ParametersInterface::class);
        $objExtension2 = new ExtensionCollectionType($type2, $nestedType, $parameters2);

        $type3 = \Guzzle\Common\Collection::class;
        $parameters3 = $this->createMock(ParametersInterface::class);
        $objExtension3 = new ExtensionCollectionType($type3, $nestedType, $parameters3);

        $class = ArrayCollection::class;
        $objectType = new ObjectType($class);

        $interface = Collection::class;
        $interfaceType = new InterfaceType($interface);

        self::assertTrue($objExtension->isClass());
        self::assertFalse($objExtension->isInterface());
        self::assertTrue($objExtension->extendsClass($objectType));
        self::assertFalse($objExtension->extendsClass($objExtension2));
        self::assertTrue($objExtension->implementsInterface($interfaceType));

        self::assertFalse($objExtension2->isClass());
        self::assertTrue($objExtension2->isInterface());
        self::assertFalse($objExtension2->extendsClass($objectType));
        self::assertFalse($objExtension2->extendsClass($objExtension));
        self::assertFalse($objExtension2->implementsInterface($interfaceType));

        self::assertTrue($objExtension3->isClass());
        self::assertFalse($objExtension3->isInterface());
        self::assertFalse($objExtension3->extendsClass($objectType));
        self::assertFalse($objExtension3->extendsClass($objExtension));
        self::assertFalse($objExtension3->implementsInterface($interfaceType));
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ExtensionCollectionType(
            Collection::class,
            new StringType(),
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
            [new ExtensionType('MyType', new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionType('MyType1', new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
            [new ExtensionCollectionType(
                Collection::class,
                new StringType(),
                new Parameters([new Parameter('name', 'value')])
            ), true],
            [new ExtensionCollectionType(
                \Guzzle\Common\Collection::class,
                new StringType(),
                new Parameters([new Parameter('name', 'value')])
            ), false],
            [new ExtensionCollectionType(
                Collection::class,
                new IntegerType(),
                new Parameters([new Parameter('name', 'value')])
            ), false],
        ];
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new ExtensionCollectionType(
            Collection::class,
            new StringType(),
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
            [new ObjectType(Car::class), false],
            [new ObjectType(ArrayCollection::class), true],
            [new InterfaceType(CarInterface::class), false],
            [new InterfaceType(Collection::class), true],
            [new StringType(), false],
            [new UnknownType(), true],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionType('MyType', new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionType('MyType1', new Parameters([new Parameter('name', 'value')])), false],
            [new ExtensionObjectType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), true],
            [new ExtensionCollectionType(
                Collection::class,
                new StringType(),
                new Parameters([new Parameter('name', 'value')])
            ), true],
            [new ExtensionCollectionType(
                ArrayCollection::class,
                new StringType(),
                new Parameters([])
            ), true],
            [new ExtensionCollectionType(
                \Guzzle\Common\Collection::class,
                new StringType(),
                new Parameters([new Parameter('name', 'value')])
            ), false],
            [new ExtensionCollectionType(
                Collection::class,
                new IntegerType(),
                new Parameters([new Parameter('name', 'value')])
            ), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        $type = new ExtensionCollectionType(
            Collection::class,
            new StringType(),
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

        self::assertSame(
            sprintf('%s::%s<%s>(%s="%s")', TypeEnum::CUSTOM_COLLECTION, Collection::class, 'string', 'name', 'value'),
            (string) $type
        );
    }
}
