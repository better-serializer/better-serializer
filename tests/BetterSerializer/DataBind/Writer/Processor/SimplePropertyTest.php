<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
class SimplePropertyTest extends TestCase
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

        $converterMock = $this->getMockBuilder(ConverterInterface::class)->getMock();
        $converterMock->expects(self::once())
            ->method('convert')
            ->with($extractedValue)
            ->willReturn($extractedValue);

        /* @var $extractorMock ExtractorInterface */
        /* @var $converterMock ConverterInterface */
        /* @var $contextMock ContextInterface */
        $processor = new SimpleProperty($extractorMock, $converterMock, $outputKey);
        $processor->process($contextMock, $instance);
    }
}
