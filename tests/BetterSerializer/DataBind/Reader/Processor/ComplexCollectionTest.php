<?php
declare(strict_types=1);

/**
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
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $key1 = 0;
        $key2 = 1;

        $arrayData = [
            $key1 => [],
            $key2 => [],
        ];

        $subContextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $subContextMock->expects(self::exactly(2))
            ->method('getDeserialized')
            ->willReturn($instance);
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
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
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(2))
            ->method('process')
            ->withConsecutive([$subContextMock], [$subContextMock]);

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testProcessEmpty(): void
    {
        $arrayData = [];

        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($arrayData);
        $contextMock->expects(self::exactly(0))
            ->method('readSubContext');
        $contextMock->expects(self::once())
            ->method('setDeserialized')
        ->with([]);
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(0))
            ->method('process');

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock);
    }
}
