<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
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
            $key1 => $instance,
            $key2 => $instance,
        ];

        $subContextMock = $this->createMock(ContextInterface::class);
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::exactly(2))
            ->method('createSubContext')
            ->willReturn($subContextMock);
        $contextMock->expects(self::exactly(2))
            ->method('mergeSubContext')
            ->withConsecutive([0, $subContextMock], [1, $subContextMock]);
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(2))
            ->method('process')
            ->withConsecutive([$subContextMock, $instance], [$subContextMock, $instance]);

        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock, $arrayData);
    }

    /**
     *
     */
    public function testProcessEmpty(): void
    {
        $arrayData = [];

        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::exactly(0))
            ->method('createSubContext');
        $contextMock->expects(self::exactly(0))
            ->method('mergeSubContext');
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(0))
            ->method('process');

        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock, $arrayData);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $subProcessor = $this->createMock(ComplexProcessorInterface::class);
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
