<?php
declare(strict_types=1);

/*
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
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WriterTest extends TestCase
{

    /**
     *
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testWriteValueAsString(): void
    {
        $toSerialize = new Radio('test');
        $serializationType = SerializationType::NONE();
        $serializedData = 'serialized';

        $type = $this->createMock(TypeInterface::class);

        $typeExtractor = $this->createMock(ExtractorInterface::class);
        $typeExtractor->expects(self::once())
            ->method('extract')
            ->with($toSerialize)
            ->willReturn($type);

        $typeContext = $this->createMock(ContextInterface::class);
        $typeContext->expects(self::once())
            ->method('getData')
            ->willReturn($serializedData);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects(self::once())
            ->method('process')
            ->with($typeContext, $toSerialize);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($processor);

        $contextFactory = $this->createMock(ContextFactoryInterface::class);
        $contextFactory->expects(self::once())
            ->method('createContext')
            ->with($serializationType)
            ->willReturn($typeContext);

        $context = $this->createMock(SerializationContextInterface::class);

        $writer = new Writer($typeExtractor, $processorFactory, $contextFactory);
        $output = $writer->writeValueAsString($toSerialize, $serializationType, $context);

        self::assertSame($serializedData, $output);
    }
}
