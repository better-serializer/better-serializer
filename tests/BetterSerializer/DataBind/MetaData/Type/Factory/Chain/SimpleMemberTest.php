<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class SimpleMemberTest extends TestCase
{

    /**
     * @dataProvider typeMappingProvider
     * @param string $stringType
     * @param string $typeClassName
     */
    public function testGetType(string $stringType, string $typeClassName): void
    {
        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::exactly(2))
            ->method('getStringType')
            ->willReturn($stringType);
        /* @var $context StringTypedPropertyContextInterface */

        $simpleMember = new SimpleMember();
        $typeObject = $simpleMember->getType($context);

        self::assertInstanceOf($typeClassName, $typeObject);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $context = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getStringType')
            ->willReturn(Car::class);
        /* @var $context StringTypedPropertyContextInterface */

        $simpleMember = new SimpleMember();
        $shouldBeNull = $simpleMember->getType($context);

        self::assertNull($shouldBeNull);
    }

    /**
     * @return array
     */
    public function typeMappingProvider(): array
    {
        return [
            [TypeEnum::BOOLEAN, BooleanType::class],
            [TypeEnum::NULL, NullType::class],
            [TypeEnum::INTEGER, IntegerType::class],
            [TypeEnum::FLOAT, FloatType::class],
            [TypeEnum::STRING, StringType::class],
        ];
    }
}
