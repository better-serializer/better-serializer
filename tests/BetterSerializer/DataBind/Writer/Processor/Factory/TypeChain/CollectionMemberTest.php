<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexCollection;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\SimpleCollection;
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
        $nestedType = $this->getMockBuilder(TypeInterface::class)->getMock();
        /* @var $nestedType TypeInterface */
        $arrayType = new ArrayType($nestedType);

        $converterFactory = $this->getMockBuilder(ConverterFactoryInterface::class)->getMock();
        $converterFactory->expects(self::exactly(0))
            ->method('newConverter');

        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($nestedType)
            ->willReturn($processor);

        /* @var $converterFactory ConverterFactoryInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        $collectionMember = new CollectionMember($converterFactory, $processorFactory);
        $collectionProcessor = $collectionMember->create($arrayType);

        self::assertInstanceOf(ComplexCollection::class, $collectionProcessor);
    }

    /**
     *
     */
    public function testCreateSimple(): void
    {
        $nestedType = new StringType();
        $arrayType = new ArrayType($nestedType);

        $converter = $this->getMockBuilder(ConverterInterface::class)->getMock();

        $converterFactory = $this->getMockBuilder(ConverterFactoryInterface::class)->getMock();
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->with($nestedType)
            ->willReturn($converter);

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::exactly(0))
            ->method('createFromType');

        /* @var $converterFactory ConverterFactoryInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        $collectionMember = new CollectionMember($converterFactory, $processorFactory);
        $collectionProcessor = $collectionMember->create($arrayType);

        self::assertInstanceOf(SimpleCollection::class, $collectionProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonArrayType = $this->getMockBuilder(TypeInterface::class)->getMock();
        $converterFactory = $this->getMockBuilder(ConverterFactoryInterface::class)->getMock();
        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();

        /* @var $converterFactory ConverterFactoryInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        $collectionMember = new CollectionMember($converterFactory, $processorFactory);
        /* @var  $nonArrayType TypeInterface */
        $shouldBeNull = $collectionMember->create($nonArrayType);

        self::assertNull($shouldBeNull);
    }
}
