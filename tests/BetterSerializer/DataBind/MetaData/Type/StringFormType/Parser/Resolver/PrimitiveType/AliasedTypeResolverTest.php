<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResultInterface;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class AliasedTypeResolverTest extends TestCase
{

    /**
     *
     */
    public function testFormatResultDecoration(): void
    {
        $expectedResult = $this->createMock(ResultInterface::class);
        $formatResult = $this->createMock(FormatResultInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $typeResolver = $this->createMock(ResolverInterface::class);
        $typeResolver->expects(self::once())
            ->method('resolve')
            ->with(new IsInstanceOf(AliasedFormatResult::class), $context)
            ->willReturn($expectedResult);

        $aliasedResolver = new AliasedTypeResolver($typeResolver);
        $result = $aliasedResolver->resolve($formatResult, $context);

        self::assertSame($expectedResult, $result);
    }
}
