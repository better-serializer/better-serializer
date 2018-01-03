<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\RadioInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ComplexPropertyProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $outputKey = 'key';
        $instance = $this->createMock(CarInterface::class);
        $subInstance = $this->createMock(RadioInterface::class);
        $subContextMock = $this->createMock(ContextInterface::class);
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('createSubContext')
            ->willReturn($subContextMock);
        $contextMock->expects(self::once())
            ->method('mergeSubContext')
            ->with($outputKey, $subContextMock);

        $extractorMock = $this->createMock(ExtractorInterface::class);
        $extractorMock->expects(self::once())
            ->method('extract')
            ->with($instance)
            ->willReturn($subInstance);

        $complexNestedMock = $this->createMock(PropertyProcessorInterface::class);
        $complexNestedMock->expects(self::once())
            ->method('process')
            ->with($subContextMock, $subInstance);

        $processor = new ComplexPropertyProcessor($extractorMock, $complexNestedMock, $outputKey);
        $processor->process($contextMock, $instance);
    }

    /**
     *
     */
    public function testProcessNull(): void
    {
        $outputKey = 'key';
        $instance = null;
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('write')
            ->with($outputKey, null);

        $extractorMock = $this->createMock(ExtractorInterface::class);

        $complexNestedMock = $this->createMock(PropertyProcessorInterface::class);
        $complexNestedMock->expects(self::exactly(0))
            ->method('process');

        $processor = new ComplexPropertyProcessor($extractorMock, $complexNestedMock, $outputKey);
        $processor->process($contextMock, $instance);
    }

    /**
     *
     */
    public function testProcessExtractedNull(): void
    {
        $outputKey = 'key';
        $instance = $this->createMock(CarInterface::class);
        $subContextMock = $this->createMock(ContextInterface::class);
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('write')
            ->with($outputKey, null);
        $contextMock->expects(self::once())
            ->method('createSubContext')
            ->willReturn($subContextMock);

        $extractorMock = $this->createMock(ExtractorInterface::class);
        $extractorMock->expects(self::once())
            ->method('extract')
            ->with($instance)
            ->willReturn(null);

        $complexNestedMock = $this->createMock(PropertyProcessorInterface::class);
        $complexNestedMock->expects(self::exactly(0))
            ->method('process');

        $processor = new ComplexPropertyProcessor($extractorMock, $complexNestedMock, $outputKey);
        $processor->process($contextMock, $instance);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $inputKey = 'key';
        $subProcessor = $this->createMock(PropertyProcessorInterface::class);
        $subProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $extractorMock = $this->createMock(ExtractorInterface::class);

        $processor = new ComplexPropertyProcessor($extractorMock, $processorMock, $inputKey);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unexpected processor instance: [a-zA-Z0-9_\\]+/
     */
    public function testConstructionThrowsRuntimeException(): void
    {
        $inputKey = 'key';
        $processorMock = $this->createMock(ProcessorInterface::class);
        $extractorMock = $this->createMock(ExtractorInterface::class);

        new ComplexPropertyProcessor($extractorMock, $processorMock, $inputKey);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unexpected processor instance: [a-zA-Z0-9_\\]+/
     */
    public function testResolveRecursiveProcessorsThrowsRuntimeException(): void
    {
        $inputKey = 'key';
        $subProcessor = $this->createMock(ProcessorInterface::class);

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $extractorMock = $this->createMock(ExtractorInterface::class);

        $processor = new ComplexPropertyProcessor($extractorMock, $processorMock, $inputKey);
        $processor->resolveRecursiveProcessors();
    }
}
