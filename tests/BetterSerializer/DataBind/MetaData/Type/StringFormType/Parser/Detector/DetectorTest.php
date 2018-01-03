<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ParserChainInterface as FormatParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResultInterface as ResolverResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use PHPUnit\Framework\TestCase;
use LogicException;

/**
 *
 */
class DetectorTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testDetectTypeReturnsResult(): void
    {
        $typeString1 = 'test';
        $typeString2 = 'test2';
        $typeStringConcat = "{$typeString1}|{$typeString2}";

        $formatResult = $this->createMock(FormatResultInterface::class);
        $formatParser = $this->createMock(FormatParserInterface::class);
        $formatParser->expects(self::exactly(2))
            ->method('parse')
            ->withConsecutive([$typeString1], [$typeString2])
            ->willReturn($formatResult);

        $typeClass1 = TypeClassEnum::UNKNOWN_TYPE();
        $typeClass2 = $this->createMock(TypeClassEnumInterface::class);
        $resolverResult = $this->createMock(ResolverResultInterface::class);
        $resolverResult->expects(self::exactly(2))
            ->method('getTypeClass')
            ->willReturnOnConsecutiveCalls($typeClass1, $typeClass2);
        $typeResolver = $this->createMock(ResolverChainInterface::class);
        $typeResolver->expects(self::exactly(2))
            ->method('resolve')
            ->with($formatResult)
            ->willReturn($resolverResult);

        $context = $this->createMock(ContextInterface::class);

        $detector = new Detector($formatParser, $typeResolver);
        $result = $detector->detectType($typeStringConcat, $context);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($formatResult, $result->getFormatResult());
        self::assertSame($resolverResult, $result->getResolverResult());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Invalid type: .+/
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testDetectTypeThrows(): void
    {
        $typeString = 'test';

        $formatResult = $this->createMock(FormatResultInterface::class);
        $formatParser = $this->createMock(FormatParserInterface::class);
        $formatParser->expects(self::once())
            ->method('parse')
            ->with($typeString)
            ->willReturn($formatResult);

        $typeClass = TypeClassEnum::UNKNOWN_TYPE();
        $resolverResult = $this->createMock(ResolverResultInterface::class);
        $resolverResult->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);
        $typeResolver = $this->createMock(ResolverChainInterface::class);
        $typeResolver->expects(self::once())
            ->method('resolve')
            ->with($formatResult)
            ->willReturn($resolverResult);

        $context = $this->createMock(ContextInterface::class);

        $detector = new Detector($formatParser, $typeResolver);
        $detector->detectType($typeString, $context);
    }
}
