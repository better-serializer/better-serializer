<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class ParserChainTest extends TestCase
{

    /**
     *
     */
    public function testParseReturnsResult(): void
    {
        $typeFormat = 'testType';
        $result = $this->createMock(ResultInterface::class);
        $subParser1 = $this->createMock(FormatParserInterface::class);
        $subParser1->expects(self::once())
            ->method('parse')
            ->with($typeFormat)
            ->willReturn(null);
        $subParser2 = $this->createMock(FormatParserInterface::class);
        $subParser2->expects(self::once())
            ->method('parse')
            ->with($typeFormat)
            ->willReturn($result);

        $parserChain = new ParserChain([$subParser1, $subParser2]);
        self::assertSame($result, $parserChain->parse($typeFormat));
    }

    /**
     *
     */
    public function testParseReturnsDefaultResult(): void
    {
        $typeFormat = 'testType';
        $subParser1 = $this->createMock(FormatParserInterface::class);
        $subParser1->expects(self::once())
            ->method('parse')
            ->with($typeFormat)
            ->willReturn(null);

        $parserChain = new ParserChain([$subParser1]);
        $result = $parserChain->parse($typeFormat);

        self::assertInstanceOf(ResultInterface::class, $result);
        self::assertSame($typeFormat, $result->getType());
        self::assertNull($result->getParameters());
        self::assertNull($result->getNestedValueType());
        self::assertNull($result->getNestedKeyType());
    }
}
