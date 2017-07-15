<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
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
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($instance);
        $instantiatorMock = $this->getMockBuilder(InstantiatorInterface::class)->getMock();
        $instantiatorMock->expects(self::once())
            ->method('construct')
            ->with($contextMock)
            ->willReturn($instance);
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(2))
                      ->method('process')
                      ->with($contextMock);

        /* @var $contextMock ContextInterface */
        /* @var $instantiatorMock InstantiatorInterface */
        $processor = new Object($instantiatorMock, [$processorMock, $processorMock]);
        $processor->process($contextMock);
    }
}
