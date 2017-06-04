<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Constructor\ConstructorInterface;
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
        $constructorMock = $this->getMockBuilder(ConstructorInterface::class)->getMock();
        $constructorMock->expects(self::once())
            ->method('construct')
            ->willReturn($instance);
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($instance);
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(2))
                      ->method('process')
                      ->with($contextMock);

        /* @var $contextMock ContextInterface */
        /* @var $constructorMock ConstructorInterface */
        $processor = new Object($constructorMock, [$processorMock, $processorMock]);
        $processor->process($contextMock);
    }
}
