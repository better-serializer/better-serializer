<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\InterfaceType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class InterfaceMemberTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $stringTypeString = CarInterface::class;
        $stringType = $this->createMock(StringFormTypeInterface::class);
        $stringType->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->method('isInterface')
            ->willReturn(true);

        $objectMember = new InterfaceMember();
        /* @var $interfaceType InterfaceType */
        $interfaceType = $objectMember->getType($stringType);

        self::assertInstanceOf(InterfaceType::class, $interfaceType);
        self::assertSame($interfaceType->getInterfaceName(), $stringTypeString);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $stringType = $this->createMock(StringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('isInterface')
            ->willReturn(false);

        $objectMember = new InterfaceMember();
        $shouldBeNull = $objectMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }
}
