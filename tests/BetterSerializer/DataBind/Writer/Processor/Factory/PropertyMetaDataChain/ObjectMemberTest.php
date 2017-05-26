<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ObjectProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\ObjectProperty;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;
use LogicException;

/**
 * Class ObjectMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ObjectMemberTest extends TestCase
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
        $type = new ObjectType(Car::class);
        $propertyMetaData = Mockery::mock(PropertyMetaDataInterface::class);
        $propertyMetaData->shouldReceive('getType')
            ->twice()
            ->andReturn($type)
            ->getMock()
            ->shouldReceive('getOutputKey')
            ->once()
            ->andReturn('test')
            ->getMock();

        $objProcessor = Mockery::mock(ObjectProcessorInterface::class);

        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);
        $processorFactory->shouldReceive('createFromType')
            ->once()
            ->with($type)
            ->andReturn($objProcessor)
            ->getMock();

        $extractor = Mockery::mock(ExtractorInterface::class);

        $extractorFactory = Mockery::mock(ExtractorFactoryInterface::class);
        $extractorFactory->shouldReceive('newExtractor')
            ->once()
            ->andReturn($extractor)
            ->getMock();

        $objectMember = new ObjectMember($processorFactory, $extractorFactory);
        $processor = $objectMember->create($propertyMetaData);

        self::assertInstanceOf(ObjectProperty::class, $processor);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Invalid processor type: '[a-zA-Z0-9_]+'/
     */
    public function testCreateThrowsException(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = Mockery::mock(PropertyMetaDataInterface::class);
        $propertyMetaData->shouldReceive('getType')
            ->twice()
            ->andReturn($type)
            ->getMock();

        $objProcessor = Mockery::mock(ProcessorInterface::class);

        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);
        $processorFactory->shouldReceive('createFromType')
            ->once()
            ->with($type)
            ->andReturn($objProcessor)
            ->getMock();

        $extractor = Mockery::mock(ExtractorInterface::class);

        $extractorFactory = Mockery::mock(ExtractorFactoryInterface::class);
        $extractorFactory->shouldReceive('newExtractor')
            ->once()
            ->andReturn($extractor)
            ->getMock();

        $objectMember = new ObjectMember($processorFactory, $extractorFactory);
        $objectMember->create($propertyMetaData);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new StringType();
        $propertyMetaData = Mockery::mock(PropertyMetaDataInterface::class);
        $propertyMetaData->shouldReceive('getType')
            ->once()
            ->andReturn($type)
            ->getMock();

        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);
        $extractorFactory = Mockery::mock(ExtractorFactoryInterface::class);

        $objectMember = new ObjectMember($processorFactory, $extractorFactory);
        $shouldBeNull = $objectMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
