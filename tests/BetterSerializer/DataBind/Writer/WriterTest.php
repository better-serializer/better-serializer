<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

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
    public function testWriteValueAsString(): void
    {
        $toSerialize = new Radio('test');
        $serializationType = SerializationType::NONE();
        $serializedData = 'serialized';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $typeExtractor = $this->getMockBuilder(ExtractorInterface::class)->getMock();
        $typeExtractor->expects(self::once())
            ->method('extract')
            ->with($toSerialize)
            ->willReturn($type);

        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getData')
            ->willReturn($serializedData);

        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processor->expects(self::once())
            ->method('process')
            ->with($context, $toSerialize);

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($processor);

        $contextFactory = $this->getMockBuilder(ContextFactoryInterface::class)->getMock();
        $contextFactory->expects(self::once())
            ->method('createContext')
            ->with($serializationType)
            ->willReturn($context);

        /* @var $typeExtractor ExtractorInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $contextFactory ContextFactoryInterface */
        $writer = new Writer($typeExtractor, $processorFactory, $contextFactory);
        $output = $writer->writeValueAsString($toSerialize, $serializationType);

        self::assertSame($serializedData, $output);
    }
}
