<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeImmutable;

/**
 * Class DateTimeMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
class DateTimeMemberTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @param string $stringTypeString
     * @param string $className
     * @param string $format
     * @param string $namespace
     */
    public function testGetType(string $stringTypeString, string $className, string $format, string $namespace): void
    {
        $stringType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->expects(self::any())
            ->method('getNamespace')
            ->willReturn($namespace);
        /* @var $stringType StringFormTypeInterface */

        $dateTimeMember = new DateTimeMember();
        /* @var $typeObject DateTimeType */
        $typeObject = $dateTimeMember->getType($stringType);

        self::assertInstanceOf(DateTimeType::class, $typeObject);
        self::assertSame($className, $typeObject->getClassName());
        self::assertSame($format, $typeObject->getFormat());
    }

    /**
     * @return array
     */
    public function classNameProvider(): array
    {
        return [
            ["DateTime(format='Y-m-d')", DateTime::class, 'Y-m-d', ''],
            ['DateTime', DateTime::class, DateTime::ATOM, ''],
            ["DateTime(format='Y-m-d')", DateTime::class, 'Y-m-d', 'Asd\\Dto\\'],
            ['DateTime', DateTime::class, DateTime::ATOM, 'Asd\\Dto\\'],
            ["\\DateTime(format='Y-m-d')", DateTime::class, 'Y-m-d', ''],
            ['\\DateTime', DateTime::class, DateTime::ATOM, ''],
            ["\\DateTime(format='Y-m-d')", DateTime::class, 'Y-m-d', 'Asd\\Dto\\'],
            ['\\DateTime', DateTime::class, DateTime::ATOM, 'Asd\\Dto\\'],
            ['\\DateTime', DateTime::class, DateTime::ATOM, 'BetterSerializer\\Dto\\'],
            ["DateTimeImmutable(format='Y-m-d')", DateTimeImmutable::class, 'Y-m-d', ''],
            ['DateTimeImmutable', DateTimeImmutable::class, DateTime::ATOM, ''],
            ["DateTimeImmutable(format='Y-m-d')", DateTimeImmutable::class, 'Y-m-d', 'Asd\\Dto\\'],
            ['DateTimeImmutable', DateTimeImmutable::class, DateTime::ATOM, 'Asd\\Dto\\'],
            ["\\DateTimeImmutable(format='Y-m-d')", DateTimeImmutable::class, 'Y-m-d', ''],
            ['\\DateTimeImmutable', DateTimeImmutable::class, DateTime::ATOM, ''],
            ["\\DateTimeImmutable(format='Y-m-d')", DateTimeImmutable::class, 'Y-m-d', 'Asd\\Dto\\'],
            ['\\DateTimeImmutable', DateTimeImmutable::class, DateTime::ATOM, 'Asd\\Dto\\'],
            ['\\DateTimeImmutable', DateTimeImmutable::class, DateTime::ATOM, 'BetterSerializer\\Dto\\'],
        ];
    }

    /**
     * @dataProvider wrongClassNameProvider
     * @param string $stringTypeString
     * @param string $namespace
     */
    public function testGetTypeReturnsNull(string $stringTypeString, string $namespace): void
    {
        $stringType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->expects(self::any())
            ->method('getNamespace')
            ->willReturn($namespace);
        /* @var $stringType StringFormTypeInterface */

        $dateTimeMember = new DateTimeMember();
        /* @var $typeObject DateTimeType */
        $typeObject = $dateTimeMember->getType($stringType);

        self::assertNull($typeObject);
    }

    /**
     * @return array
     */
    public function wrongClassNameProvider(): array
    {
        return [
            ['DateTimer', ''],
            ['abc', ''],
            ['BetterSerializer\\Dto\\DateTime', 'Test']
        ];
    }
}
