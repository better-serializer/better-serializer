<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexCollection;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\SimpleCollection;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CollectionMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreateComplex(): void
    {
        $nestedType = $this->createMock(TypeInterface::class);
        $arrayType = new ArrayType($nestedType);

        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $converterFactory->expects(self::exactly(0))
            ->method('newConverter');

        $processor = $this->createMock(ProcessorInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($nestedType, $context)
            ->willReturn($processor);


        $collectionMember = new CollectionMember($converterFactory, $processorFactory);
        $collectionProcessor = $collectionMember->create($arrayType, $context);

        self::assertInstanceOf(ComplexCollection::class, $collectionProcessor);
    }

    /**
     *
     */
    public function testCreateSimple(): void
    {
        $nestedType = new StringType();
        $arrayType = new ArrayType($nestedType);

        $converter = $this->createMock(ConverterInterface::class);

        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->with($nestedType)
            ->willReturn($converter);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::exactly(0))
            ->method('createFromType');

        $context = $this->createMock(SerializationContextInterface::class);

        $collectionMember = new CollectionMember($converterFactory, $processorFactory);
        $collectionProcessor = $collectionMember->create($arrayType, $context);

        self::assertInstanceOf(SimpleCollection::class, $collectionProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonArrayType = $this->createMock(TypeInterface::class);
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $collectionMember = new CollectionMember($converterFactory, $processorFactory);
        $shouldBeNull = $collectionMember->create($nonArrayType, $context);

        self::assertNull($shouldBeNull);
    }
}
