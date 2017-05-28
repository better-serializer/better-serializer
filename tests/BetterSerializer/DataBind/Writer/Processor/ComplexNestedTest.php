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

/**
 * Class NestedObjectTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
class ComplexNestedTest extends TestCase
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

        $complexNestedMock = $this->getMockBuilder(ComplexNestedProcessorInterface::class)->getMock();
        $complexNestedMock->expects(self::once())
            ->method('process')
            ->with($subContextMock, $subInstance);

        /* @var $extractorMock ExtractorInterface */
        /* @var $complexNestedMock ComplexNestedProcessorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new ComplexNested($extractorMock, $complexNestedMock, $outputKey);
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

        $complexNestedMock = $this->getMockBuilder(ComplexNestedProcessorInterface::class)->getMock();
        $complexNestedMock->expects(self::exactly(0))
            ->method('process');

        /* @var $extractorMock ExtractorInterface */
        /* @var $complexNestedMock ComplexNestedProcessorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new ComplexNested($extractorMock, $complexNestedMock, $outputKey);
        $processor->process($contextMock, $instance);
    }
}
