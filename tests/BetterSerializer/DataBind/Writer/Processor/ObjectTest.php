<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
class ObjectTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $outputKey = 'key';
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $subContextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
                    ->method('createSubContext')
                    ->willReturn($subContextMock);
        $contextMock->expects(self::once())
                    ->method('mergeSubContext')
                    ->with($outputKey, $subContextMock);

        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(2))
                      ->method('process')
                      ->with($subContextMock, $instance);

        /* @var $contextMock ContextInterface */
        $processor = new Object([$processorMock, $processorMock], $outputKey);
        $processor->process($contextMock, $instance);
    }
}
