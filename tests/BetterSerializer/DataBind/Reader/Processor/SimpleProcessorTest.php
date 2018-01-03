<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class SimpleProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $inValue = '6';
        $outValue = 6;

        $converter = $this->createMock(ConverterInterface::class);
        $converter->expects(self::once())
            ->method('convert')
            ->with($inValue)
            ->willReturn($outValue);

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($inValue);
        $context->expects(self::once())
            ->method('setDeserialized')
            ->with($outValue);

        $simple = new SimpleProcessor($converter);
        $simple->process($context);
    }
}
