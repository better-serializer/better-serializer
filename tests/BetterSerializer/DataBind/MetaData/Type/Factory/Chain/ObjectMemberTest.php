<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ObjectMemberTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $stringTypeString = Car::class;
        $stringType = $this->createMock(StringFormTypeInterface::class);
        $stringType->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->method('isClass')
            ->willReturn(true);

        $objectMember = new ObjectMember();
        /* @var $typeObject ObjectType */
        $typeObject = $objectMember->getType($stringType);

        self::assertInstanceOf(ObjectType::class, $typeObject);
        self::assertSame($typeObject->getClassName(), $stringTypeString);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $stringType = $this->createMock(StringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('isClass')
            ->willReturn(false);

        $objectMember = new ObjectMember();
        $shouldBeNull = $objectMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }
}
