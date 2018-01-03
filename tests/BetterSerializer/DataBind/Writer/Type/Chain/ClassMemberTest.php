<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ClassMemberTest extends TestCase
{

    /**
     * @dataProvider objectProvider
     * @param mixed $data
     * @param string $className
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetType($data, string $className): void
    {
        $objectMember = new ClassMember();
        /* @var $type ClassType */
        $type = $objectMember->getType($data);

        self::assertInstanceOf(ClassType::class, $type);
        self::assertSame($className, $type->getClassName());
    }

    /**
     * @dataProvider nonObjectProvider
     * @param mixed $data
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetTypeReturnsNull($data): void
    {
        $objectMember = new ClassMember();
        $type = $objectMember->getType($data);

        self::assertNull($type);
    }

    /**
     * @return array
     */
    public function objectProvider(): array
    {
        return [
            [new Car('test', 'test', new Radio('test')), Car::class],
            [new Radio('test'), Radio::class],
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
        ];
    }
}
