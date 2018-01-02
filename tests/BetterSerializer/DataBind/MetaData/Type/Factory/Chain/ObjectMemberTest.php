<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\Dto\Car;
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
        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn(TypeClassEnum::CLASS_TYPE());

        $objectMember = new ObjectMember();
        /* @var $typeObject ObjectType */
        $typeObject = $objectMember->getType($stringType);

        self::assertNotNull($typeObject);
        self::assertInstanceOf(ObjectType::class, $typeObject);
        self::assertSame($typeObject->getClassName(), $stringTypeString);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $typeClass = $this->createMock(TypeClassEnumInterface::class);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $objectMember = new ObjectMember();
        $shouldBeNull = $objectMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }
}
