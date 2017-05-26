<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
class ObjectMemberTest extends TestCase
{

    /**
     * @dataProvider classNameProvider
     * @param string $className
     */
    public function testGetType(string $className): void
    {
        $objectMember = new ObjectMember();
        /* @var $typeObject ObjectType */
        $typeObject = $objectMember->getType($className);

        self::assertInstanceOf(ObjectType::class, $typeObject);
        self::assertSame($typeObject->getClassName(), $className);
    }

    /**
     * @return array
     */
    public function classNameProvider(): array
    {
        return [
            [Car::class],
            [Radio::class],
        ];
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $objectMember = new ObjectMember();
        $shouldBeNull = $objectMember->getType(TypeEnum::STRING);

        self::assertNull($shouldBeNull);
    }
}
