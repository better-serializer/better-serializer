<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleCollectionTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
class SimpleCollectionTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $key1 = 0;
        $key2 = 1;

        $arrayData = [
            $key1 => 'test',
            $key2 => 'test2',
        ];

        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($arrayData);
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($arrayData);

        $converterMock = $this->getMockBuilder(ConverterInterface::class)->getMock();
        $converterMock->expects(self::exactly(2))
            ->method('convert')
            ->withConsecutive([$arrayData[$key1]], [$arrayData[$key2]])
            ->willReturnOnConsecutiveCalls($arrayData[$key1], $arrayData[$key2]);

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        /* @var $converterMock ConverterInterface */
        $processor = new SimpleCollection($converterMock);
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
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($arrayData);

        $converterMock = $this->getMockBuilder(ConverterInterface::class)->getMock();

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        /* @var $converterMock ConverterInterface */
        $processor = new SimpleCollection($converterMock);
        $processor->process($contextMock);
    }
}
