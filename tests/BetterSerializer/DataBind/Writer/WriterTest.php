<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
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

        $type = Mockery::mock(TypeInterface::class);
        $type->shouldReceive('getType');

        $typeExtractor = Mockery::mock(ExtractorInterface::class);
        $typeExtractor->shouldReceive('extract')
            ->once()
            ->with($toSerialize)
            ->andReturn($type)
            ->getMock();

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
        $processorFactory->shouldReceive('createFromType')
                         ->once()
                         ->with($type)
                         ->andReturn($processor)
                         ->getMock();


        $contextFactory = Mockery::mock(ContextFactoryInterface::class);
        $contextFactory->shouldReceive('createContext')
                       ->with($serializationType)
                       ->andReturn($context)
                       ->getMock();

        /* @var $typeExtractor ExtractorInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $contextFactory ContextFactoryInterface */
        $writer = new Writer($typeExtractor, $processorFactory, $contextFactory);
        $output = $writer->writeValueAsString($toSerialize, $serializationType);

        self::assertSame($serializedData, $output);
    }
}
