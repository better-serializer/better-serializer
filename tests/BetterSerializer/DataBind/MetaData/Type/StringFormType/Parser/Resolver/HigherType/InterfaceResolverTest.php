<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Car2;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\SpecialCarInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class InterfaceResolverTest extends TestCase
{

    /**
     * @param string $stringType
     * @param TypeClassEnum|null $expectedTypeClass
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider resolveDataProvider
     */
    public function testResolve(string $stringType, ?TypeClassEnum $expectedTypeClass): void
    {
        $resolver = new InterfaceResolver();
        $typeClass = $resolver->resolveTypeClass($stringType);

        self::assertSame($expectedTypeClass, $typeClass);
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function resolveDataProvider(): array
    {
        return [
            [Car::class, null],
            [Car2::class, null],
            [CarInterface::class, TypeClassEnum::INTERFACE_TYPE()],
            [SpecialCarInterface::class, TypeClassEnum::INTERFACE_TYPE()],
        ];
    }
}
