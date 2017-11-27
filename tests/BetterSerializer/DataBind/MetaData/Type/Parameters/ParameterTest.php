<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Parameters;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class ParameterTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $name = 'test';
        $value = 66;

        $parameter = new Parameter($name, $value);

        self::assertSame($name, $parameter->getName());
        self::assertSame($value, $parameter->getValue());
        self::assertSame(sprintf('%s="%s"', $name, $value), (string) $parameter);
    }

    /**
     * @param Parameter $cmpParameter
     * @param bool $result
     * @dataProvider equalsDataProvider
     */
    public function testEquals(Parameter $cmpParameter, bool $result): void
    {
        $parameter = new Parameter('test1', '1');

        self::assertSame($parameter->equals($cmpParameter), $result);
        self::assertSame($cmpParameter->equals($parameter), $result);
    }

    /**
     * @return array
     */
    public function equalsDataProvider(): array
    {
        return [
            [new Parameter('test1', 'value1'), false],
            [new Parameter('test2', 'value1'), false],
            [new Parameter('test1', 'value2'), false],
            [new Parameter('test1', 1), false],
            [new Parameter('test1', 2), false],
            [new Parameter('test1', '1'), true],
            [new Parameter('test2', 1), false],
        ];
    }
}
