<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\ValueWriter\ValueWriterInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
class PropertyTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $extractedValue = 5;
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();

        $extractorMock = $this->getMockBuilder(ExtractorInterface::class)->getMock();
        $extractorMock->expects(self::once())
                      ->method('extract')
                      ->with($instance)
                      ->willReturn($extractedValue);

        $writerMock = $this->getMockBuilder(ValueWriterInterface::class)->getMock();
        $writerMock->expects(self::once())
                   ->method('writeValue')
                   ->with($contextMock, $extractedValue);

        /* @var $extractorMock ExtractorInterface */
        /* @var $writerMock ValueWriterInterface */
        /* @var $contextMock ContextInterface */
        $processor = new Property($extractorMock, $writerMock);
        $processor->process($contextMock, $instance);
    }
}
