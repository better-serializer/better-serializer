<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

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

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new SimpleCollection();
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

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new SimpleCollection();
        $processor->process($contextMock);
    }
}
