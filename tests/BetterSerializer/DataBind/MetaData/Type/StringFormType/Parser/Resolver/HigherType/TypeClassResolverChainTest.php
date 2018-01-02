<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class TypeClassResolverChainTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolveSuccess(): void
    {
        $potentialHigherType = Car::class;
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $resolver = $this->createMock(TypeClassResolverInterface::class);
        $resolver->expects(self::once())
            ->method('resolveTypeClass')
            ->with($potentialHigherType)
            ->willReturn($typeClass);

        $chain = new TypeClassResolverChain([$resolver]);

        self::assertSame($typeClass, $chain->resolveTypeClass($potentialHigherType));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolveFallback(): void
    {
        $potentialHigherType = 'unknown';
        $typeClass = null;
        $resolver = $this->createMock(TypeClassResolverInterface::class);
        $resolver->expects(self::once())
            ->method('resolveTypeClass')
            ->with($potentialHigherType)
            ->willReturn(null);

        $chain = new TypeClassResolverChain([$resolver]);

        self::assertSame($typeClass, $chain->resolveTypeClass($potentialHigherType));
    }
}
