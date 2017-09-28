<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeImmutable;

/**
 * Class DateTimeMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type\Chain
 */
class DateTimeMemberTest extends TestCase
{
    /**
     * @dataProvider objectProvider
     * @param mixed $data
     * @param string $className
     */
    public function testGetType($data, string $className): void
    {
        $objectMember = new DateTimeMember();
        /* @var $type DateTimeType */
        $type = $objectMember->getType($data);

        self::assertInstanceOf(DateTimeType::class, $type);
        self::assertSame($className, $type->getClassName());
    }

    /**
     * @dataProvider nonObjectProvider
     * @param mixed $data
     */
    public function testGetTypeReturnsNull($data): void
    {
        $objectMember = new DateTimeMember();
        $type = $objectMember->getType($data);

        self::assertNull($type);
    }

    /**
     * @return array
     */
    public function objectProvider(): array
    {
        return [
            [new DateTime(), DateTime::class],
            [new DateTimeImmutable(), DateTimeImmutable::class],
        ];
    }

    /**
     * @return array
     */
    public function nonObjectProvider(): array
    {
        return [
            [[1]],
            [true],
            [false],
            [null],
            [1],
            [0],
            [-1],
            [0.1],
            [0.0],
            [-0.1],
            ['test'],
            [''],
            [new Car('a', 'b', new Radio('c'))]
        ];
    }
}
