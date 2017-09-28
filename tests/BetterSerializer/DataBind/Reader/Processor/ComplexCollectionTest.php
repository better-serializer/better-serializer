<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
class ComplexCollectionTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $instance = $this->createMock(CarInterface::class);
        $key1 = 0;
        $key2 = 1;

        $arrayData = [
            $key1 => [],
            $key2 => [],
        ];

        $subContextMock = $this->createMock(ContextInterface::class);
        $subContextMock->expects(self::exactly(2))
            ->method('getDeserialized')
            ->willReturn($instance);
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($arrayData);
        $contextMock->expects(self::exactly(2))
            ->method('readSubContext')
            ->withConsecutive([$key1], [$key2])
            ->willReturn($subContextMock);
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with([$instance, $instance]);
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(2))
            ->method('process')
            ->withConsecutive([$subContextMock], [$subContextMock]);

        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testProcessEmpty(): void
    {
        $arrayData = [];

        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($arrayData);
        $contextMock->expects(self::exactly(0))
            ->method('readSubContext');
        $contextMock->expects(self::once())
            ->method('setDeserialized')
        ->with([]);
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(0))
            ->method('process');

        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $subProcessor = $this->createMock(ComplexNestedProcessorInterface::class);
        $subProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $processor = new ComplexCollection($processorMock);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }
}
