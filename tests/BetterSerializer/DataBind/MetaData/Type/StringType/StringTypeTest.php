<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringType;

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

        $context = new StringType($type, $namespace);

        self::assertSame($type, $context->getStringType());
        self::assertSame($namespace, $context->getNamespace());
    }
}
