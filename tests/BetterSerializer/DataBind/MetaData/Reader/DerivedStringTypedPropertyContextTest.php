<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use PHPUnit\Framework\TestCase;

/**
 * Class DerivedStringTypedPropertyContextTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
class DerivedStringTypedPropertyContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = 'string';
        $namespace = 'test';

        $context = new DerivedStringTypedPropertyContext($type, $namespace);

        self::assertSame($type, $context->getStringType());
        self::assertSame($namespace, $context->getNamespace());
    }
}
