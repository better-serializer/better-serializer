<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\ObjectPropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\Factory\AbstractFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Object as ObjectProcessor;
use BetterSerializer\DataBind\Writer\Processor\ObjectProperty as ObjectPropertyProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\Property as PropertyProcessor;
use BetterSerializer\DataBind\Writer\ValueWriter\Property as PropertyValueWriter;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;

/**
 * Class ProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 * @SuppressWarnings(PHPMD)
 */
class ProcessorFactoryTest extends TestCase
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
    public function testCreate(): void
    {
        $carPropertyTitle = Mockery::mock(PropertyMetaDataInterface::class);
        $carPropertyTitle->shouldReceive('getOutputKey')
                     ->once()
                     ->andReturn('title')
                     ->getMock();
        $carPropertyRadio = Mockery::mock(ObjectPropertyMetadataInterface::class);
        $carPropertyRadio->shouldReceive('getOutputKey')
                         ->once()
                         ->andReturn('radio')
                         ->getMock()
                         ->shouldReceive('getObjectClass')
                         ->once()
                         ->andReturn(Radio::class)
                         ->getMock();
        $radioPropertyBrand = Mockery::mock(PropertyMetadataInterface::class);
        $radioPropertyBrand->shouldReceive('getOutputKey')
                           ->once()
                           ->andReturn('brand')
                           ->getMock();

        $carMetaData = Mockery::mock(MetaDataInterface::class);
        $carMetaData->shouldReceive('getPropertiesMetadata')
                    ->once()
                    ->andReturn(['title' => $carPropertyTitle, 'radio' => $carPropertyRadio]);
        $radioMetaData = Mockery::mock(MetaDataInterface::class);
        $radioMetaData->shouldReceive('getPropertiesMetadata')
                      ->once()
                      ->andReturn(['brand' => $radioPropertyBrand]);

        $metaDataReader = Mockery::mock(ReaderInterface::class);
        $metaDataReader->shouldReceive('read')
                       ->with(Car::class)
                       ->once()
                       ->andReturn($carMetaData)
                       ->getMock()
                       ->shouldReceive('read')
                       ->with(Radio::class)
                       ->once()
                       ->andReturn($radioMetaData);

        $propertyExtractor = Mockery::mock(ExtractorInterface::class);

        $extractorFactory = Mockery::mock(AbstractFactoryInterface::class);
        $extractorFactory->shouldReceive('newExtractor')
                         ->with($carPropertyTitle)
                         ->once()
                         ->andReturn($propertyExtractor)
                         ->getMock()
                         ->shouldReceive('newExtractor')
                         ->with($carPropertyRadio)
                         ->once()
                         ->andReturn($propertyExtractor)
                         ->getMock()
                         ->shouldReceive('newExtractor')
                         ->with($radioPropertyBrand)
                         ->once()
                         ->andReturn($propertyExtractor);

        /* @var $metaDataReader ReaderInterface */
        /* @var $extractorFactory AbstractFactoryInterface */
        $processorFactory = new ProcessorFactory($metaDataReader, $extractorFactory);
        $processor = $processorFactory->create(Car::class);

        self::assertInstanceOf(ProcessorInterface::class, $processor);
        self::assertInstanceOf(ObjectProcessor::class, $processor);

        $objectReflClass = new ReflectionClass(ObjectProcessor::class);
        $processorsProperty = $objectReflClass->getProperty('processors');
        $processorsProperty->setAccessible(true);

        $objectProcessors = $processorsProperty->getValue($processor);
        self::assertInternalType('array', $objectProcessors);
        self::assertCount(2, $objectProcessors);

        $propertyReflClass = new ReflectionClass(PropertyProcessor::class);
        $valueWriterProperty = $propertyReflClass->getProperty('valueWriter');
        $valueWriterProperty->setAccessible(true);

        $valueWriterReflClass = new ReflectionClass(PropertyValueWriter::class);
        $outputKeyProperty2 = $valueWriterReflClass->getProperty('outputKey');
        $outputKeyProperty2->setAccessible(true);

        $titleProcessor = $objectProcessors[0];
        $titleValueWriter = $valueWriterProperty->getValue($titleProcessor);

        self::assertInstanceOf(PropertyProcessor::class, $titleProcessor);
        self::assertSame('title', $outputKeyProperty2->getValue($titleValueWriter));

        $objectPropertyReflClass = new ReflectionClass(ObjectPropertyProcessor::class);
        $outputKeyProperty = $objectPropertyReflClass->getProperty('outputKey');
        $outputKeyProperty->setAccessible(true);
        $objProcessorProperty = $objectPropertyReflClass->getProperty('objectProcessor');
        $objProcessorProperty->setAccessible(true);

        $radioProcessor = $objectProcessors[1];
        $radioOutputKey = $outputKeyProperty->getValue($radioProcessor);
        $radioObjProcessor = $objProcessorProperty->getValue($radioProcessor);

        self::assertInstanceOf(ObjectPropertyProcessor::class, $radioProcessor);
        self::assertSame('radio', $radioOutputKey);
        self::assertInstanceOf(ObjectProcessor::class, $radioObjProcessor);

        $radioProcessors = $processorsProperty->getValue($radioObjProcessor);

        self::assertInternalType('array', $radioProcessors);
        self::assertCount(1, $radioProcessors);

        $brandProcessor = $radioProcessors[0];
        $brandValueWriter = $valueWriterProperty->getValue($brandProcessor);
        self::assertInstanceOf(PropertyProcessor::class, $brandProcessor);
        self::assertSame('brand', $outputKeyProperty2->getValue($brandValueWriter));
    }
}
