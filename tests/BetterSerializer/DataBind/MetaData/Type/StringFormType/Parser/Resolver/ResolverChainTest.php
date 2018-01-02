<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ResolverChainTest extends TestCase
{

    /**
     *
     */
    public function testResolveSuccess(): void
    {
        $formatResult = $this->createMock(FormatResultInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $result = $this->createMock(ResultInterface::class);
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->expects(self::once())
            ->method('resolve')
            ->with($formatResult, $context)
            ->willReturn($result);

        $resolverChain = new ResolverChain([$resolver]);

        self::assertSame($result, $resolverChain->resolve($formatResult, $context));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolveReturnsFallback(): void
    {
        $typeName = 'unknown';
        $formatResult = $this->createMock(FormatResultInterface::class);
        $formatResult->expects(self::once())
            ->method('getType')
            ->willReturn($typeName);
        $context = $this->createMock(ContextInterface::class);
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->expects(self::once())
            ->method('resolve')
            ->with($formatResult, $context)
            ->willReturn(null);

        $resolverChain = new ResolverChain([$resolver]);
        $result = $resolverChain->resolve($formatResult, $context);

        self::assertNotNull($result);
        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($typeName, $result->getTypeName());
        self::assertSame(TypeClassEnum::UNKNOWN_TYPE(), $result->getTypeClass());
    }
}
