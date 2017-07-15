<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ComplexParamProcessorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor
 */
class ComplexParamProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcessParam(): void
    {
        $testObj = $this->getMockBuilder(CarInterface::class)->getMock();

        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($testObj);

        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processor->expects(self::once())
            ->method('process');

        /* @var $processor ProcessorInterface */
        /* @var $context ContextInterface */
        $paramProcessor = new ComplexParamProcessor($processor);
        $processedObj = $paramProcessor->processParam($context);

        self::assertSame($testObj, $processedObj);
    }
}
