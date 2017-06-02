<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleCollectionTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
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
        $contextMock->expects(self::exactly(2))
            ->method('write')
            ->withConsecutive([$key1, $arrayData[$key1]], [$key2, $arrayData[$key2]]);

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new SimpleCollection();
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
            ->method('write');

        /* @var $contextMock ContextInterface */
        /* @var $processorMock ProcessorInterface */
        $processor = new SimpleCollection();
        $processor->process($contextMock, $arrayData);
    }
}
