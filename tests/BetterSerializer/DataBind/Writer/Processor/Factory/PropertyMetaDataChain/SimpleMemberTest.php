<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Property;
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
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

        $converter = $this->getMockBuilder(ConverterInterface::class)->getMock();

        $converterFactory = $this->getMockBuilder(ConverterFactoryInterface::class)->getMock();
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->willReturn($converter);

        $extractor = $this->getMockBuilder(ExtractorInterface::class)->getMock();

        $extractorFactory = $this->getMockBuilder(ExtractorFactoryInterface::class)->getMock();
        $extractorFactory->expects(self::once())
            ->method('newExtractor')
            ->willReturn($extractor);

        /* @var $converterFactory ConverterFactoryInterface */
        /* @var $extractorFactory ExtractorFactoryInterface */
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $simpleMember = new SimpleMember($converterFactory, $extractorFactory);
        $processor = $simpleMember->create($propertyMetaData);

        self::assertInstanceOf(Property::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $converterFactory = $this->getMockBuilder(ConverterFactoryInterface::class)->getMock();
        $extractorFactory = $this->getMockBuilder(ExtractorFactoryInterface::class)->getMock();

        /* @var $converterFactory ConverterFactoryInterface */
        /* @var $extractorFactory ExtractorFactoryInterface */
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $simpleMember = new SimpleMember($converterFactory, $extractorFactory);
        $shouldBeNull = $simpleMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
