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
 *
 */
class ClassProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(2))
                      ->method('process')
                      ->with($contextMock, $instance);

        /* @var $contextMock ContextInterface */
        $processor = new ClassProcessor([$processorMock, $processorMock]);
        $processor->process($contextMock, $instance);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $subProcessor = $this->createMock(PropertyProcessorInterface::class);
        $subProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $processor = new ClassProcessor([$processorMock]);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }
}
