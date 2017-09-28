<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader
 */
class ReaderTest extends TestCase
{

    /**
     *
     */
    public function testReadValue(): void
    {
        $serialized = 'test';
        $stringType = 'testType';
        $serializationType = $this->getMockBuilder(SerializationTypeInterface::class)->getMock();
        $deserialized = 'deserialized';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($deserialized);

        $contextFactory = $this->getMockBuilder(ContextFactoryInterface::class)->getMock();
        $contextFactory->expects(self::once())
            ->method('createContext')
            ->with($serialized, $serializationType)
            ->willReturn($context);

        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processor->expects(self::once())
            ->method('process')
            ->with($context);

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($processor);

        /* @var $typeFactory TypeFactoryInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $contextFactory ContextFactoryInterface */
        /* @var $serializationType SerializationTypeInterface */
        $reader = new Reader($typeFactory, $processorFactory, $contextFactory);
        $deserializedReally = $reader->readValue($serialized, $stringType, $serializationType);

        self::assertSame($deserialized, $deserializedReally);
    }
}
