<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
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
     * @param string $stringType
     * @param string $className
     * @param string $namespace
     * @param int $nsCalls
     */
    public function testGetType(string $stringType, string $className, string $namespace, int $nsCalls): void
    {
        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getStringType')
            ->willReturn($stringType);
        $context->expects(self::exactly($nsCalls))
            ->method('getNamespace')
            ->willReturn($namespace);
        /* @var $context StringTypedPropertyContextInterface */

        $objectMember = new ObjectMember();
        /* @var $typeObject ObjectType */
        $typeObject = $objectMember->getType($context);

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
        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getStringType')
            ->willReturn(TypeEnum::STRING);
        $context->expects(self::once())
            ->method('getNamespace')
            ->willReturn('');
        /* @var $context StringTypedPropertyContextInterface */

        $objectMember = new ObjectMember();
        $shouldBeNull = $objectMember->getType($context);

        self::assertNull($shouldBeNull);
    }
}
