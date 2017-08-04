<?php
declare(strict_types=1);

/**
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
     * @dataProvider classNameProvider
     * @param string $stringTypeString
     * @param string $className
     * @param string $namespace
     * @param int $nsCalls
     */
    public function testGetType(string $stringTypeString, string $className, string $namespace, int $nsCalls): void
    {
        $stringType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->expects(self::exactly($nsCalls))
            ->method('getNamespace')
            ->willReturn($namespace);
        /* @var $stringType StringFormTypeInterface */

        $objectMember = new ObjectMember();
        /* @var $typeObject ObjectType */
        $typeObject = $objectMember->getType($stringType);

        self::assertInstanceOf(ObjectType::class, $typeObject);
        self::assertSame($typeObject->getClassName(), $className);
    }

    /**
     * @return array
     */
    public function classNameProvider(): array
    {
        return [
            [Car::class, Car::class, '', 0],
            ['Radio', Radio::class, 'BetterSerializer\\Dto\\', 1],
        ];
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $stringType = $this->getMockBuilder(StringFormTypeInterface::class)->getMock();
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn(TypeEnum::STRING);
        $stringType->expects(self::once())
            ->method('getNamespace')
            ->willReturn('');
        /* @var $stringType StringFormTypeInterface */

        $objectMember = new ObjectMember();
        $shouldBeNull = $objectMember->getType($stringType);

        self::assertNull($shouldBeNull);
    }
}
