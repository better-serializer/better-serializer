<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Property;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class SimpleMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreate(): void
    {
        $type = new StringType();
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

        $converter = $this->createMock(ConverterInterface::class);

        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->willReturn($converter);

        $extractor = $this->createMock(ExtractorInterface::class);

        $extractorFactory = $this->createMock(ExtractorFactoryInterface::class);
        $extractorFactory->expects(self::once())
            ->method('newExtractor')
            ->willReturn($extractor);
        $context = $this->createMock(SerializationContextInterface::class);

        $simpleMember = new SimpleMember($converterFactory, $extractorFactory);
        $processor = $simpleMember->create($propertyMetaData, $context);

        self::assertInstanceOf(Property::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $extractorFactory = $this->createMock(ExtractorFactoryInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $simpleMember = new SimpleMember($converterFactory, $extractorFactory);
        $shouldBeNull = $simpleMember->create($propertyMetaData, $context);

        self::assertNull($shouldBeNull);
    }
}
