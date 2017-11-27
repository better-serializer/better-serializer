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
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomTypeTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = 'MyType';
        $parameters = $this->createMock(ParametersInterface::class);

        $customObject = new CustomType($type, $parameters);

        self::assertSame($type, $customObject->getCustomType());
        self::assertSame($parameters, $customObject->getParameters());
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new CustomType(
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
            [new CustomType('MyType', new Parameters([])), false],
            [new CustomType('MyType', new Parameters([new Parameter('name', 'value')])), true],
            [new CustomType('MyType1', new Parameters([new Parameter('name', 'value')])), false],
            [new CustomObjectType(Car::class, new Parameters([])), false],
        ];
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForIsCompatible
     */
    public function testIsCompatibleWith(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new CustomType(
            'MyType',
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
            [new CustomType('MyType', new Parameters([new Parameter('name', 'value')])), true],
            [new CustomType('MyType1', new Parameters([new Parameter('name', 'value')])), false],
            [new CustomObjectType(Car::class, new Parameters([])), false],
        ];
    }

    /**
     *
     */
    public function testToString(): void
    {
        $type = new CustomType(
            'MyType',
            new Parameters([
                new Parameter('name', 'value'),
            ])
        );

        self::assertSame(
            sprintf('%s<%s>(%s="%s")', TypeEnum::CUSTOM_TYPE, 'MyType', 'name', 'value'),
            (string) $type
        );
    }
}
