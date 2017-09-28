<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleParamProcessorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor
 */
class SimpleParamProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcessParam(): void
    {
        $key = 'test';
        $value = 3;
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getValue')
            ->with($key)
            ->willReturn($value);

        /* @var $context ContextInterface */
        $simpleParamProcessor = new SimpleParamProcessor($key);
        $testValue = $simpleParamProcessor->processParam($context);

        self::assertSame($value, $testValue);
    }
}
