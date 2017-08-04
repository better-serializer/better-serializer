<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use PHPUnit\Framework\TestCase;

/**
 * Class StringTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
class StringTypeTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = 'string';
        $namespace = 'test';

        $context = new StringFormType($type, $namespace);

        self::assertSame($type, $context->getStringType());
        self::assertSame($namespace, $context->getNamespace());
    }

    /**
     * @param string $type
     * @param string $namespace
     * @param bool $expectedResult
     * @dataProvider dataProviderForIsClass
     */
    public function testIsClass(string $type, string $namespace, bool $expectedResult): void
    {
        $stringFormType = new StringFormType($type, $namespace);

        self::assertSame($expectedResult, $stringFormType->isClass());
    }

    /**
     * @return array
     */
    public function dataProviderForIsClass(): array
    {
        return [
            ['int', '', false],
            ['Car', '', false],
            ['Car', 'BetterSerializer\Dto', true],
        ];
    }
}
