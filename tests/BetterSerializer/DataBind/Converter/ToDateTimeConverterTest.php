<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Class ToDateTimeConverterTest
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
class ToDateTimeConverterTest extends TestCase
{

    /**
     * @dataProvider getTestData
     * @param string $value
     * @param string $className
     * @param string $format
     */
    public function testConvert(string $value, string $className, string $format): void
    {
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('getClassName')
            ->willReturn($className);
        $type->expects(self::once())
            ->method('getFormat')
            ->willReturn($format);

        /* @var $type DateTimeTypeInterface */
        $converter = new ToDateTimeConverter($type);
        $converted = $converter->convert($value);

        self::assertInstanceOf($className, $converted);
        self::assertSame($value, $converted->format($format));
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            ['2017-08-19T17:31:09+00:00', DateTime::class, DateTime::ATOM],
            ['2017-08-19', DateTime::class, 'Y-m-d'],
            ['2017-08-19T17:31:09+00:00', DateTimeImmutable::class, DateTime::ATOM],
            ['2017-08-19', DateTimeImmutable::class, 'Y-m-d'],
            ['1503163869', DateTimeImmutable::class, 'U'],
        ];
    }

    /**
     *
     */
    public function testConvertNull(): void
    {
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();

        /* @var $type DateTimeTypeInterface */
        $converter = new ToDateTimeConverter($type);
        $converted = $converter->convert(null);

        self::assertNull($converted);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /Invalid type: [a-zA-Z0-9_]+\./
     */
    public function testConvertThrowsOnConstruction(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $type TypeInterface */
        new ToDateTimeConverter($type);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /The date "[^"]+" with format "[a-zA-Z0-9\-:_]+" is not valid\./
     */
    public function testConvertThrowsOnInvalidValueOrFormat(): void
    {
        $value = 3;
        $format = '1qq1';

        $class = new class extends DateTime {
            public static function getLastErrors()
            {
                return ['errors' => ['Made-up error']];
            }
        };

        $reflClass = new ReflectionClass($class);
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('getClassName')
            ->willReturn($reflClass->getName());
        $type->expects(self::once())
            ->method('getFormat')
            ->willReturn($format);

        /* @var $type DateTimeTypeInterface */
        $converter = new ToDateTimeConverter($type);
        $converter->convert($value);
    }
}
