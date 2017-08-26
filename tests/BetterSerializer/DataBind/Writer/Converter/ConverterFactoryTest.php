<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Converter;

use BetterSerializer\DataBind\Converter\BooleanConverter;
use BetterSerializer\DataBind\Converter\FloatConverter;
use BetterSerializer\DataBind\Converter\FromDateTimeConverter;
use BetterSerializer\DataBind\Converter\IntegerConverter;
use BetterSerializer\DataBind\Converter\StringConverter;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use LogicException;
use DateTime;
use DateTimeImmutable;

/**
 * Class ConverterFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Converter
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConverterFactoryTest extends TestCase
{

    /**
     * @dataProvider getTestData
     * @param TypeInterface $type
     * @param string $converterClass
     */
    public function testNewConverter(TypeInterface $type, string $converterClass): void
    {
        $factory = new ConverterFactory();
        $converter = $factory->newConverter($type);

        self::assertInstanceOf($converterClass, $converter);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            [new BooleanType(), BooleanConverter::class],
            [new FloatType(), FloatConverter::class],
            [new IntegerType(), IntegerConverter::class],
            [new StringType(), StringConverter::class],
            [new DateTimeType(DateTime::class), FromDateTimeConverter::class],
            [new DateTimeType(DateTimeImmutable::class), FromDateTimeConverter::class],
            [new DateTimeType(DateTimeImmutable::class), FromDateTimeConverter::class],
        ];
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unsupported type: [a-zA-Z0-9]+/
     */
    public function testnewConverterThrowsException(): void
    {
        $factory = new ConverterFactory();
        $factory->newConverter(new NullType());
    }
}
