<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context;

use PHPUnit\Framework\TestCase;

/**
 * Class StringTypedPropertyContextTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
class StringTypedPropertyContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $innerContext = $this->getMockBuilder(PropertyContextInterface::class)->getMock();
        $innerContext->expects(self::once())
            ->method('getNamespace')
            ->willReturn('test');

        /* @var $innerContext PropertyContextInterface */
        $context = new StringFormTypedPropertyContext($innerContext, 'array');

        self::assertSame('test', $context->getNamespace());
        self::assertSame('array', $context->getStringType());
        self::assertTrue($context->isClass());
    }
}
