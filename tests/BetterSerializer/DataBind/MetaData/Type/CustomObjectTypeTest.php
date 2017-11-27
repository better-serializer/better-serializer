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
use BetterSerializer\Dto\Car2;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomObjectTypeTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $className = Car::class;
        $parameters = $this->createMock(ParametersInterface::class);

        $customObject = new CustomObjectType($className, $parameters);

        self::assertSame($className, $customObject->getClassName());
        self::assertSame($className, $customObject->getCustomType());
        self::assertSame($parameters, $customObject->getParameters());
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new CustomObjectType(
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
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new CustomType('MyType', new Parameters([])), false],
            [new CustomObjectType(Car::class, new Parameters([])), false],
            [new CustomObjectType(Car::class, new Parameters([new Parameter('name', 'value')])), true],
            [new CustomObjectType(Car2::class, new Parameters([new Parameter('name', 'value')])), false],
        ];
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new CustomObjectType(
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
            [new ObjectType(Car::class), false],
            [new StringType(), false],
            [new UnknownType(), true],
            [new CustomType('MyType', new Parameters([])), false],
            [new CustomObjectType(Car::class, new Parameters([])), false],
            [new CustomObjectType(Car::class, new Parameters([new Parameter('name', 'value')])), true],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        $type = new CustomObjectType(
            Car::class,
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

        self::assertSame(
            sprintf('%s<%s>(%s="%s")', TypeEnum::CUSTOM_OBJECT, Car::class, 'name', 'value'),
            (string) $type
        );
    }
}
