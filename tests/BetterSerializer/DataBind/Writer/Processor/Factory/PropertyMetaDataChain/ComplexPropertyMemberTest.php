<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\PropertyProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexPropertyProcessor;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ComplexPropertyMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreate(): void
    {
        $type = new ClassType(Car::class);
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

        $objProcessor = $this->createMock(PropertyProcessorInterface::class);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($objProcessor);

        $extractor = $this->createMock(ExtractorInterface::class);

        $extractorFactory = $this->createMock(ExtractorFactoryInterface::class);
        $extractorFactory->expects(self::once())
            ->method('newExtractor')
            ->willReturn($extractor);

        $context = $this->createMock(SerializationContextInterface::class);

        $complexNestedMember = new ComplexPropertyMember($processorFactory, $extractorFactory);
        $processor = $complexNestedMember->create($propertyMetaData, $context);

        self::assertInstanceOf(ComplexPropertyProcessor::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new StringType();
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $extractorFactory = $this->createMock(ExtractorFactoryInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $complexNestedMember = new ComplexPropertyMember($processorFactory, $extractorFactory);
        $shouldBeNull = $complexNestedMember->create($propertyMetaData, $context);

        self::assertNull($shouldBeNull);
    }
}
