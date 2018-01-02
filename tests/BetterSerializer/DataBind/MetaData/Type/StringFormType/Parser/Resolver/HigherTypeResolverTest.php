<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResult;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType\TypeClassResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType\TypeGuesserInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class HigherTypeResolverTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolveSuccess(): void
    {
        $potentialHigherType = Car::class;
        $typeClass = TypeClassEnum::CLASS_TYPE();
        $typeGuesser = $this->createMock(TypeGuesserInterface::class);
        $typeGuesser->expects(self::once())
            ->method('guess')
            ->with($potentialHigherType)
            ->willReturn($potentialHigherType);
        $typeClassResolver = $this->createMock(TypeClassResolverChainInterface::class);
        $typeClassResolver->expects(self::once())
            ->method('resolveTypeClass')
            ->willReturn($typeClass);
        $formatResult = $this->createMock(FormatResult::class);
        $formatResult->expects(self::once())
            ->method('getType')
            ->willReturn($potentialHigherType);
        $context = $this->createMock(ContextInterface::class);

        $resolver = new HigherTypeResolver([$typeGuesser], $typeClassResolver);
        $result = $resolver->resolve($formatResult, $context);

        self::assertNotNull($result);
        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($potentialHigherType, $result->getTypeName());
        self::assertSame($typeClass, $result->getTypeClass());
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolveReturnsNull(): void
    {
        $potentialHigherType = 'unknown';

        $typeGuesser = $this->createMock(TypeGuesserInterface::class);
        $typeGuesser->expects(self::once())
            ->method('guess')
            ->with($potentialHigherType)
            ->willReturn($potentialHigherType);
        $typeClassResolver = $this->createMock(TypeClassResolverChainInterface::class);
        $typeClassResolver->expects(self::once())
            ->method('resolveTypeClass')
            ->willReturn(null);
        $formatResult = $this->createMock(FormatResult::class);
        $formatResult->expects(self::once())
            ->method('getType')
            ->willReturn($potentialHigherType);
        $context = $this->createMock(ContextInterface::class);

        $resolver = new HigherTypeResolver([$typeGuesser], $typeClassResolver);
        $result = $resolver->resolve($formatResult, $context);

        self::assertNull($result);
    }
}
