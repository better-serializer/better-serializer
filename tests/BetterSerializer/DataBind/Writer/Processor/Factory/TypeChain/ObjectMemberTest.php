<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\ObjectPropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Object;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class ObjectMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain
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
        $objectType = new ObjectType(Car::class);
        $property1 = Mockery::mock(PropertyMetaDataInterface::class);
        $property2 = Mockery::mock(ObjectPropertyMetadataInterface::class);
        $metaData = Mockery::mock(MetaDataInterface::class);
        $metaData->shouldReceive('getPropertiesMetadata')
            ->once()
            ->andReturn(['title' => $property1, 'radio' => $property2]);

        $metaDataReader = Mockery::mock(ReaderInterface::class);
        $metaDataReader->shouldReceive('read')
            ->with(Car::class)
            ->once()
            ->andReturn($metaData)
            ->getMock();

        $processor = Mockery::mock(ProcessorInterface::class);

        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);
        $processorFactory->shouldReceive('createFromMetaData')
            ->once()
            ->with($property1)
            ->andReturn($processor)
            ->getMock()
            ->shouldReceive('createFromMetaData')
            ->with($property2)
            ->andReturn($processor)
            ->getMock();

        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $metaDataReader ReaderInterface */
        $objectMember = new ObjectMember($processorFactory, $metaDataReader);
        $objProcessor = $objectMember->create($objectType);

        self::assertInstanceOf(Object::class, $objProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonObjectType = Mockery::mock(TypeInterface::class);
        $metaDataReader = Mockery::mock(ReaderInterface::class);
        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);

        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $metaDataReader ReaderInterface */
        $objectMember = new ObjectMember($processorFactory, $metaDataReader);
        /* @var  $nonObjectType TypeInterface */
        $shouldBeNull = $objectMember->create($nonObjectType);

        self::assertNull($shouldBeNull);
    }
}
