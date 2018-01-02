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
class SerializerFormatParserTest extends TestCase
{

    /**
     * @param string $typeFormat
     * @param string $expectedType
     * @param null|string $expectedParams
     * @param null|string $expNestedValueType
     * @param null|string $expNestedKeyType
     * @dataProvider parseDataProvider
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testParse(
        string $typeFormat,
        string $expectedType,
        ?string $expectedParams,
        ?string $expNestedValueType,
        ?string $expNestedKeyType
    ): void {
        $parser = new SerializerFormatParser();
        $result = $parser->parse($typeFormat);

        self::assertNotNull($result);
        self::assertSame($expectedType, $result->getType());
        self::assertSame($expectedParams, $result->getParameters());
        self::assertSame($expNestedValueType, $result->getNestedValueType());
        self::assertSame($expNestedKeyType, $result->getNestedKeyType());
    }

    /**
     * @return array
     */
    public function parseDataProvider(): array
    {
        return [
            ['string', 'string', null, null, null],
            ['Collection<string>', 'Collection', null, 'string', null],
            ['Collection<string>()', 'Collection', null, 'string', null],
            ['Collection<string>(  )', 'Collection', null, 'string', null],
            ['Collection<int, string>', 'Collection', null, 'string', 'int'],
            ['Collection< int ,   string >', 'Collection', null, 'string', 'int'],
            ['Collection<int, string>(param1=2)', 'Collection', 'param1=2', 'string', 'int'],
            [
                "Collection<int, string>(param1=2, param2='test')",
                'Collection',
                "param1=2, param2='test'",
                'string',
                'int'
            ],
            [
                "Collection<    int,     string >(   param1=2,  param2='test'  )",
                'Collection',
                "param1=2,  param2='test'",
                'string',
                'int'
            ],
        ];
    }

    /**
     * @param string $typeFormat
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider parseReturnsNullDataProvider
     */
    public function testParseReturnsNull(string $typeFormat): void
    {
        $parser = new SerializerFormatParser();
        $result = $parser->parse($typeFormat);

        self::assertNull($result);
    }

    /**
     * @return array
     */
    public function parseReturnsNullDataProvider(): array
    {
        return [
            ['string[]'],
            ['<asd>'],
            ['[asd]'],
        ];
    }
}
