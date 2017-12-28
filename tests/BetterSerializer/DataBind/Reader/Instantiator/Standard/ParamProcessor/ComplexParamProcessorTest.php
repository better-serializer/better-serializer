<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\CachedProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\PropertyProcessorInterface;
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
        $key = 'test';
        $testObj = $this->createMock(CarInterface::class);

        $subContext = $this->createMock(ContextInterface::class);
        $subContext->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($testObj);

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('readSubContext')
            ->willReturn($subContext);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects(self::once())
            ->method('process');

        $paramProcessor = new ComplexParamProcessor($key, $processor);
        $processedObj = $paramProcessor->processParam($context);

        self::assertSame($testObj, $processedObj);
    }

    /**
     *
     */
    public function testProcessParamReturnsNull(): void
    {
        $key = 'test';

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('readSubContext')
            ->willReturn(null);

        $processor = $this->createMock(ProcessorInterface::class);

        $paramProcessor = new ComplexParamProcessor($key, $processor);
        $processedObj = $paramProcessor->processParam($context);

        self::assertNull($processedObj);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $key = 'test';
        $subProcessor = $this->createMock(PropertyProcessorInterface::class);
        $subProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processor = $this->createMock(CachedProcessorInterface::class);
        $processor->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $paramProcessor = new ComplexParamProcessor($key, $processor);
        $paramProcessor->resolveRecursiveProcessors();

        // lazy resolve test
        $paramProcessor->resolveRecursiveProcessors();
    }
}
