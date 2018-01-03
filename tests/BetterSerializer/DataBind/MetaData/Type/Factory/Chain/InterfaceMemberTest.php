<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\InterfaceType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class InterfaceMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $stringTypeString = CarInterface::class;
        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->method('getTypeClass')
            ->willReturn(TypeClassEnum::INTERFACE_TYPE());

        $objectMember = new InterfaceMember();
        /* @var $interfaceType InterfaceType */
        $interfaceType = $objectMember->getType($stringType);

        self::assertNotNull($interfaceType);
        self::assertInstanceOf(InterfaceType::class, $interfaceType);
        self::assertSame($interfaceType->getInterfaceName(), $stringTypeString);
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

        $objectMember = new InterfaceMember();
        $shouldBeNull = $objectMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }
}
