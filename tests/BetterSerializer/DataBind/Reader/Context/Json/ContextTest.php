<?php
declare(strict_types=1);

/**
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
        $context = new Context($json);
        $subContext = $context->readSubContext('b');

        self::assertSame('a', $context->readValue('a'));
        self::assertInstanceOf(Context::class, $subContext);
        self::assertSame('c', $subContext->readValue('c'));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid key: [a-zA-Z0-9_]+/
     */
    public function testReadValueThrowsException(): void
    {
        $json = '{"a":"a"}';
        $context = new Context($json);
        $context->readValue('b');
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
