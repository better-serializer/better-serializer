<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context\Json;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class ContextTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Context\Json
 */
class ContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $json = '{"a":"a","b":{"c":"c"}}';
        $deserialized = 'test';
        $context = new Context($json);
        $subContext = $context->readSubContext('b');

        self::assertSame('a', $context->getValue('a'));
        self::assertInstanceOf(Context::class, $subContext);
        self::assertSame('c', $subContext->getValue('c'));

        $current = $context->getCurrentValue();

        self::assertInternalType('array', $current);
        self::assertArrayHasKey('a', $current);
        self::assertSame('a', $current['a']);
        self::assertArrayHasKey('b', $current);
        self::assertInternalType('array', $current['b']);
        self::assertArrayHasKey('c', $current['b']);
        self::assertSame('c', $current['b']['c']);

        $context->setDeserialized($deserialized);
        self::assertSame($deserialized, $context->getDeserialized());
    }

    /**
     *
     */
    public function testReadSubContextReturnsNull(): void
    {
        $json = '{"a":null}';
        $context = new Context($json);
        $shouldBeNull = $context->readSubContext('a');

        self::assertNull($shouldBeNull);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid key: [a-zA-Z0-9_]+/
     */
    public function testReadValueThrowsException(): void
    {
        $json = '{"a":"a"}';
        $context = new Context($json);
        $context->getValue('b');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid key: [a-zA-Z0-9_]+/
     */
    public function testReadSubContextThrowsException(): void
    {
        $json = '{"a":"a"}';
        $context = new Context($json);
        $context->readSubContext('b');
    }
}
