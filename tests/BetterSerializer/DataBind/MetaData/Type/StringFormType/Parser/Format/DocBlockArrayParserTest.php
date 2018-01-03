<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format;

use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class DocBlockArrayParserTest extends TestCase
{

    /**
     *
     */
    public function testParseReturnsResult(): void
    {
        $stringType = 'string[]';

        $formatParser = new DocBlockArrayParser();
        $result = $formatParser->parse($stringType);

        self::assertNotNull($result);
        self::assertInstanceOf(Result::class, $result);
        self::assertSame(TypeEnum::ARRAY_TYPE, $result->getType());
        self::assertNull($result->getParameters());
        self::assertSame('string', $result->getNestedValueType());
        self::assertNull($result->getNestedKeyType());
    }

    /**
     *
     */
    public function testParseReturnsNull(): void
    {
        $stringType = Car::class;

        $formatParser = new DocBlockArrayParser();
        $result = $formatParser->parse($stringType);

        self::assertNull($result);
    }
}
