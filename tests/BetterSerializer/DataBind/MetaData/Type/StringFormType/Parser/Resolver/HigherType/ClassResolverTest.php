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
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ClassResolverTest extends TestCase
{

    /**
     * @param string $stringType
     * @param TypeClassEnum|null $expectedTypeClass
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider resolveDataProvider
     */
    public function testResolve(string $stringType, ?TypeClassEnum $expectedTypeClass): void
    {
        $resolver = new ClassResolver();
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
            [Car::class, TypeClassEnum::CLASS_TYPE()],
            [Car2::class, TypeClassEnum::CLASS_TYPE()],
            [CarInterface::class, null],
        ];
    }
}
