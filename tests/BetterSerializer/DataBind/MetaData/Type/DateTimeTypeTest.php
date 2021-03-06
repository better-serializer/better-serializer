<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Dto\SpecialCar;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

use DateTime;
use DateTimeImmutable;
use LogicException;

/**
 * Class DateTimeTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DateTimeTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $dateTime = new DateTimeType(DateTime::class);
        self::assertInstanceOf(get_class(TypeEnum::DATETIME_TYPE()), $dateTime->getType());
        self::assertSame(DateTime::class, $dateTime->getClassName());
        self::assertSame(DateTime::ATOM, $dateTime->getFormat());
        self::assertSame(
            "dateTime(class='" . DateTime::class . "', format='" . DateTime::ATOM . "')",
            (string) $dateTime
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetTypeWithCustomFormat(): void
    {
        $format = 'Y-m-d';
        $dateTime = new DateTimeType(DateTimeImmutable::class, $format);
        self::assertInstanceOf(get_class(TypeEnum::DATETIME_TYPE()), $dateTime->getType());
        self::assertSame(DateTimeImmutable::class, $dateTime->getClassName());
        self::assertSame($format, $dateTime->getFormat());
        self::assertSame(
            "dateTime(class='" . DateTimeImmutable::class . "', format='" . $format . "')",
            (string) $dateTime
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unsupported class: '[a-zA-Z0-9_\\]+'./
     */
    public function testConstructionThrowsLogicException(): void
    {
        new DateTimeType(Car::class);
    }

    /**
     * @param TypeInterface $typeToTest
     * @param bool $expectedResult
     * @dataProvider typeProviderForEquals
     */
    public function testEquals(TypeInterface $typeToTest, bool $expectedResult): void
    {
        $type = new DateTimeType(DateTime::class);

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
            [new ClassType(SpecialCar::class), false],
            [new StringType(), false],
            [new UnknownType(), false],
            [new DateTimeType(DateTime::class), true],
            [new DateTimeType(DateTime::class, 'Y-m-d'), false],
            [new DateTimeType(DateTimeImmutable::class), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([])), false],
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
        $type = new DateTimeType(DateTime::class);

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
            [new ClassType(Car::class), false],
            [new ClassType(Radio::class), false],
            [new ClassType(DateTime::class), true],
            [new StringType(), false],
            [new UnknownType(), true],
            [new DateTimeType(DateTime::class), true],
            [new DateTimeType(DateTime::class, 'Y-m-d'), true],
            [new DateTimeType(DateTimeImmutable::class), false],
            [new ExtensionType('MyType', new Parameters([])), false],
            [new ExtensionClassType(Car::class, new Parameters([])), false],
            [new ExtensionCollectionType(Collection::class, new StringType(), new Parameters([])), false],
        ];
    }
}
