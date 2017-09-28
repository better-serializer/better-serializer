<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

use PHPUnit\Framework\TestCase;

/**
 * Class IntegerConverterTest
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
class IntegerConverterTest extends TestCase
{

    /**
     * @dataProvider getTestData
     * @param mixed $value
     */
    public function testConvert($value): void
    {
        $converter = new IntegerConverter();
        $converted = $converter->convert($value);

        self::assertInternalType('int', $converted);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            [1],
            [0],
            [''],
            ['1'],
            ['0'],
            [true],
            [false],
            [1.0],
            [0.0]
        ];
    }
}
