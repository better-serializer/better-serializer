<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class PrimitiveTypeResolverTest extends TestCase
{

    /**
     * @param string $stringType
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @dataProvider resolveSuccessDataProvider
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testResolveSuccess(string $stringType): void
    {
        $formatResult = $this->createMock(FormatResultInterface::class);
        $formatResult->expects(self::once())
            ->method('getType')
            ->willReturn($stringType);
        $context = $this->createMock(ContextInterface::class);

        $resolver = new PrimitiveTypeResolver();
        $result = $resolver->resolve($formatResult, $context);

        self::assertNotNull($result);
        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($stringType, $result->getTypeName());
        self::assertSame(TypeClassEnum::PRIMITIVE_TYPE(), $result->getTypeClass());
    }

    /**
     * @return array
     */
    public function resolveSuccessDataProvider(): array
    {
        return [
            [TypeEnum::BOOLEAN_TYPE],
            [TypeEnum::ARRAY_TYPE],
            [TypeEnum::FLOAT_TYPE],
            [TypeEnum::INTEGER_TYPE],
            [TypeEnum::STRING_TYPE],
        ];
    }

    /**
     *
     */
    public function testResolveReturnsNull(): void
    {
        $stringType = 'CustomType';
        $formatResult = $this->createMock(FormatResultInterface::class);
        $formatResult->expects(self::once())
            ->method('getType')
            ->willReturn($stringType);
        $context = $this->createMock(ContextInterface::class);

        $resolver = new PrimitiveTypeResolver();
        $result = $resolver->resolve($formatResult, $context);

        self::assertNull($result);
    }
}
