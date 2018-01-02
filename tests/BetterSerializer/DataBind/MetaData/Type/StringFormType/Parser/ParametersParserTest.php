<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ParametersParserTest extends TestCase
{

    /**
     * @param string $customClassDefinition
     * @param array $expectedParameters
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider parametersDataProvider
     */
    public function testParseParameters(string $customClassDefinition, array $expectedParameters): void
    {
        $parser = new ParametersParser();
        $parameters = $parser->parseParameters($customClassDefinition);

        if (empty($expectedParameters)) {
            self::assertTrue($parameters->isEmpty());

            return;
        }

        self::assertCount(count($expectedParameters), $parameters);

        foreach ($expectedParameters as $parameterName => $parameterValue) {
            self::assertTrue($parameters->has($parameterName));
            $parameter = $parameters->get($parameterName);

            self::assertSame($parameterName, $parameter->getName());
            self::assertSame($parameterValue, $parameter->getValue());
        }
    }

    /**
     * @return array
     */
    public function parametersDataProvider(): array
    {
        return [
            [
                "asd='zxc',   bbb='1',c=2.2, d=12",
                [
                    'asd' => 'zxc',
                    'bbb' => '1',
                    'c' => 2.2,
                    'd' => 12,
                ]
            ],
            [
                "asd='zxc'",
                [
                    'asd' => 'zxc',
                ]
            ],
            [
                ' ',
                []
            ],
            [
                '',
                []
            ],
        ];
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid parameter count for raw parameter: .+/
     */
    public function testParseParametersThrowsRuntimeException(): void
    {
        $parser = new ParametersParser();
        $parser->parseParameters('asd="z=xc"');
    }
}
