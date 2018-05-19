<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context\PhpArray;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $data = ['a' => 'a', 'b' => ['c' => 'c']];
        $deserialized = 'test';
        $context = new Context($data);
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
        $data = ['a' => null];
        $context = new Context($data);
        $shouldBeNull = $context->readSubContext('a');

        self::assertNull($shouldBeNull);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid key: [a-zA-Z0-9_]+/
     */
    public function testReadValueThrowsException(): void
    {
        $data = ['a' => 'a'];
        $context = new Context($data);
        $context->getValue('b');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid key: [a-zA-Z0-9_]+/
     */
    public function testReadSubContextThrowsException(): void
    {
        $data = ['a' => 'a'];
        $context = new Context($data);
        $context->readSubContext('b');
    }
}
