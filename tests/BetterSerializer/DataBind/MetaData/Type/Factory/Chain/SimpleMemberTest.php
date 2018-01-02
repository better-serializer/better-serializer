<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SimpleMemberTest extends TestCase
{

    /**
     * @dataProvider typeMappingProvider
     * @param string $stringTypeString
     * @param string $typeClassName
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function testGetType(string $stringTypeString, string $typeClassName): void
    {
        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->expects(self::exactly(2))
            ->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn(TypeClassEnum::PRIMITIVE_TYPE());

        $simpleMember = new SimpleMember();
        $simpleType = $simpleMember->getType($stringType);

        self::assertInstanceOf($typeClassName, $simpleType);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->expects(self::once())
            ->method('getStringType')
            ->willReturn(Car::class);
        $stringType->expects(self::once())
            ->method('getTypeClass')
            ->willReturn(TypeClassEnum::PRIMITIVE_TYPE());

        $simpleMember = new SimpleMember();
        $shouldBeNull = $simpleMember->getType($stringType);

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
