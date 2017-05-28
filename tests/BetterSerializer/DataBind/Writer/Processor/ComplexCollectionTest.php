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
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $key1 = 0;
        $key2 = 1;

        $arrayData = [
            $key1 => $instance,
            $key2 => $instance,
        ];

        $subContextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::exactly(2))
            ->method('createSubContext')
            ->willReturn($subContextMock);
        $contextMock->expects(self::exactly(2))
            ->method('mergeSubContext')
            ->withConsecutive([0, $subContextMock], [1, $subContextMock]);
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(2))
            ->method('process')
            ->withConsecutive([$subContextMock, $instance], [$subContextMock, $instance]);

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock, $arrayData);
    }

    /**
     *
     */
    public function testProcessEmpty(): void
    {
        $arrayData = [];

        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::exactly(0))
            ->method('createSubContext');
        $contextMock->expects(self::exactly(0))
            ->method('mergeSubContext');
        $processorMock = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processorMock->expects(self::exactly(0))
            ->method('process');

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new ComplexCollection($processorMock);
        $processor->process($contextMock, $arrayData);
    }
}
