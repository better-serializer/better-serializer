<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Parameters;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ParserTest extends TestCase
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
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn($customClassDefinition);

        $parser = new Parser();
        $parameters = $parser->parseParameters($stringFormType);

        if (empty($expectedParameters)) {
            self::assertTrue($parameters->isEmpty());

            return;
        }

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
                "CustomClass(asd='zxc',   bbb='1',c=2.2, d=12)",
                [
                    'asd' => 'zxc',
                    'bbb' => '1',
                    'c' => 2.2,
                    'd' => 12,
                ]
            ],
            [
                "CustomClass(asd='zxc')",
                [
                    'asd' => 'zxc',
                ]
            ],
            [
                '\NamespaceX\asd',
                []
            ],
            [
                '\NamespaceX\asd()',
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
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn('CustomClass(asd="z=xc")');

        $parser = new Parser();
        $parser->parseParameters($stringFormType);
    }
}
