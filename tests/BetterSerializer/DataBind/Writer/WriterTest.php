<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class WriterTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class WriterTest extends TestCase
{

    /**
     *
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     */
    public function testWriteValueAsString(): void
    {
        $toSerialize = new Radio('test');
        $serializationType = SerializationType::NONE();
        $serializedData = 'serialized';

        $context = Mockery::mock(ContextInterface::class);
        $context->shouldReceive('getData')
            ->once()
            ->andReturn($serializedData)
            ->getMock();

        $processor = Mockery::mock(ProcessorInterface::class);
        $processor->shouldReceive('process')
                  ->with($context, $toSerialize)
                  ->once()
                  ->getMock();

        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);
        $processorFactory->shouldReceive('create')
                         ->once()
                         ->with(Radio::class)
                         ->andReturn($processor)
                         ->getMock();


        $contextFactory = Mockery::mock(ContextFactoryInterface::class);
        $contextFactory->shouldReceive('createContext')
                       ->with($serializationType)
                       ->andReturn($context)
                       ->getMock();

        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $contextFactory ContextFactoryInterface */
        $writer = new Writer($processorFactory, $contextFactory);
        $output = $writer->writeValueAsString($toSerialize, $serializationType);

        self::assertSame($serializedData, $output);
    }
}
