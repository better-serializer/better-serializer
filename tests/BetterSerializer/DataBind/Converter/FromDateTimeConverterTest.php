<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use DateTimeInterface;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Class FromDateTimeConverterTest
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
class FromDateTimeConverterTest extends TestCase
{

    /**
     * @dataProvider getTestData
     * @param DateTimeInterface $value
     * @param string $className
     * @param string $format
     */
    public function testConvert(DateTimeInterface $value, string $className, string $format): void
    {
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('getClassName')
            ->willReturn($className);
        $type->expects(self::once())
            ->method('getFormat')
            ->willReturn($format);

        /* @var $type DateTimeTypeInterface */
        $converter = new FromDateTimeConverter($type);
        $converted = $converter->convert($value);

        self::assertInternalType('string', $converted);
        self::assertSame($value->format($format), $converted);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            [new DateTime(), DateTime::class, DateTime::ATOM],
            [new DateTime(), DateTime::class, 'Y-m-d'],
            [new DateTimeImmutable(), DateTimeImmutable::class, DateTime::ATOM],
            [new DateTimeImmutable(), DateTimeImmutable::class, 'Y-m-d'],
            [new DateTimeImmutable(), DateTimeImmutable::class, 'U'],
        ];
    }

    /**
     *
     */
    public function testConvertNull(): void
    {
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();

        /* @var $type DateTimeTypeInterface */
        $converter = new FromDateTimeConverter($type);
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
        new FromDateTimeConverter($type);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /Expected a "[A-Za-z0-9_]+", got "[A-Za-z0-9_]+"\./
     */
    public function testConvertThrowsOnInvalidInput(): void
    {
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('getClassName')
            ->willReturn(DateTime::class);

        /* @var $type DateTimeTypeInterface */
        $converter = new FromDateTimeConverter($type);
        $converter->convert(1);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /The date format "[a-zA-Z0-9\-:_]+" is not valid\./
     */
    public function testConvertThrowsOnInvalidFormat(): void
    {
        $format = '1qq1';
        $type = $this->getMockBuilder(DateTimeTypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('getClassName')
            ->willReturn(DateTime::class);
        $type->expects(self::once())
            ->method('getFormat')
            ->willReturn($format);

        $value = $this->getMockBuilder(DateTime::class)->getMock();
        $value->expects(self::once())
            ->method('format')
            ->with($format)
            ->willReturn(false);

        /* @var $type DateTimeTypeInterface */
        $converter = new FromDateTimeConverter($type);
        $converter->convert($value);
    }
}
