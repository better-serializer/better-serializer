<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ReaderTest extends TestCase
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
    public function testReadValue(): void
    {
        $serialized = 'test';
        $typeString = 'testType';
        $serializationType = $this->createMock(SerializationTypeInterface::class);
        $deserialized = 'deserialized';

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringTypeParser->expects(self::once())
            ->method('parseSimple')
            ->with($typeString)
            ->willReturn($stringType);

        $type = $this->createMock(TypeInterface::class);
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->with($stringType)
            ->willReturn($type);

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($deserialized);

        $contextFactory = $this->createMock(ContextFactoryInterface::class);
        $contextFactory->expects(self::once())
            ->method('createContext')
            ->with($serialized, $serializationType)
            ->willReturn($context);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects(self::once())
            ->method('process')
            ->with($context);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($processor);

        $reader = new Reader($stringTypeParser, $typeFactory, $processorFactory, $contextFactory);
        $deserializedReally = $reader->readValue($serialized, $typeString, $serializationType);

        self::assertSame($deserialized, $deserializedReally);
    }
}
