<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ParametersTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $name1 = 'test';
        $value1 = '1';
        $parameter1 = $this->createMock(ParameterInterface::class);
        $parameter1->method('getName')
            ->willReturn($name1);
        $parameter1->method('__toString')
            ->willReturn(sprintf('%s="%s"', $name1, $value1));

        $name2 = 'test2';
        $value2 = '2';
        $parameter2 = $this->createMock(ParameterInterface::class);
        $parameter2->method('getName')
            ->willReturn($name2);
        $parameter2->method('__toString')
            ->willReturn(sprintf('%s="%s"', $name2, $value2));

        $parameters = new Parameters([$parameter1, $parameter2]);

        self::assertTrue($parameters->has($name1));
        self::assertFalse($parameters->has('non-existent'));
        self::assertFalse($parameters->isEmpty());
        self::assertSame($parameter1, $parameters->get($name1));
        self::assertSame($parameters->count(), 2);
        self::assertSame(sprintf('%s="%s", %s="%s"', $name1, $value1, $name2, $value2), (string) $parameters);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Parameter missing: [a-zA-Z0-9]+/
     */
    public function testThrowsRuntimeExceptionOnGettingNonExistentParameter(): void
    {
        $name = 'test';

        $parameters = new Parameters([]);

        self::assertFalse($parameters->has($name));
        self::assertTrue($parameters->isEmpty());
        $parameters->get($name);
    }

    /**
     * @param array $cmpParametersArray
     * @param bool $result
     * @dataProvider equalsDataProvider
     */
    public function testEquals(array $cmpParametersArray, bool $result): void
    {
        $parameters = new Parameters([new Parameter('name1', '1'), new Parameter('name2', 'value2')]);
        $cmpParameters = new Parameters($cmpParametersArray);

        self::assertSame($parameters->equals($cmpParameters), $result);
        self::assertSame($cmpParameters->equals($parameters), $result);
    }

    /**
     * @return array
     */
    public function equalsDataProvider(): array
    {
        return [
            [[new Parameter('name1', '1'), new Parameter('name2', 'value2')], true],
            [[new Parameter('name2', 'value2'), new Parameter('name1', '1')], true],
            [[new Parameter('name2', 'value1'), new Parameter('name1', 'value2')], false],
            [[new Parameter('name1', 1), new Parameter('name2', 'value2')], false],
            [[new Parameter('name1', '1'), new Parameter('name2', 'value22')], false],
            [[new Parameter('name1', 'value1')], false],
            [[new Parameter('name2', 'value2')], false],
            [[new Parameter('name3', 'value3'), new Parameter('name2', 'value22')], false],
            [[
                new Parameter('name1', '1'),
                new Parameter('name2', 'value2'),
                new Parameter('name3', 'value3'),
            ], false],
        ];
    }
}
