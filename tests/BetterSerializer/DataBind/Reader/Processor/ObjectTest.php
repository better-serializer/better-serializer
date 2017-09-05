<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Instantiator\ProcessingInstantiatorInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
class ObjectTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $instance = $this->createMock(CarInterface::class);
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($instance);
        $instantiatorMock = $this->createMock(InstantiatorInterface::class);
        $instantiatorMock->expects(self::once())
            ->method('instantiate')
            ->with($contextMock)
            ->willReturn($instance);
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(2))
                      ->method('process')
                      ->with($contextMock);

        $processor = new Object($instantiatorMock, [$processorMock, $processorMock]);
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

        $instantiatorMock = $this->createMock(ProcessingInstantiatorInterface::class);
        $instantiatorMock->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processor = new Object($instantiatorMock, [$processorMock]);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }
}
