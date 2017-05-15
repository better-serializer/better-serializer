<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\RadioInterface;
use PHPUnit\Framework\TestCase;

class ObjectPropertyTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $outputKey = 'key';
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $subInstance = $this->getMockBuilder(RadioInterface::class)->getMock();
        $subContextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('createSubContext')
            ->willReturn($subContextMock);
        $contextMock->expects(self::once())
            ->method('mergeSubContext')
            ->with($outputKey, $subContextMock);

        $extractorMock = $this->getMockBuilder(ExtractorInterface::class)->getMock();
        $extractorMock->expects(self::once())
            ->method('extract')
            ->with($instance)
            ->willReturn($subInstance);

        $objectProcessorMock = $this->getMockBuilder(ObjectProcessorInterface::class)->getMock();
        $objectProcessorMock->expects(self::once())
            ->method('process')
            ->with($subContextMock, $subInstance);

        /* @var $extractorMock ExtractorInterface */
        /* @var $objectProcessorMock ObjectProcessorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new ObjectProperty($extractorMock, $objectProcessorMock, $outputKey);
        $processor->process($contextMock, $instance);
    }

    /**
     *
     */
    public function testProcessNull(): void
    {
        $outputKey = 'key';
        $instance = null;
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('write')
            ->with($outputKey, null);

        $extractorMock = $this->getMockBuilder(ExtractorInterface::class)->getMock();

        $objectProcessorMock = $this->getMockBuilder(ObjectProcessorInterface::class)->getMock();
        $objectProcessorMock->expects(self::exactly(0))
            ->method('process');

        /* @var $extractorMock ExtractorInterface */
        /* @var $objectProcessorMock ObjectProcessorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new ObjectProperty($extractorMock, $objectProcessorMock, $outputKey);
        $processor->process($contextMock, $instance);
    }
}
