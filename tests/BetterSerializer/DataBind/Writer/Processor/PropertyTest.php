<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Context\ContextInterface;
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
        $outputKey = 'test';

        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
                    ->method('write')
                    ->with($outputKey, $extractedValue);

        $extractorMock = $this->getMockBuilder(ExtractorInterface::class)->getMock();
        $extractorMock->expects(self::once())
                      ->method('extract')
                      ->with($instance)
                      ->willReturn($extractedValue);

        /* @var $extractorMock ExtractorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new Property($extractorMock, $outputKey);
        $processor->process($contextMock, $instance);
    }
}
