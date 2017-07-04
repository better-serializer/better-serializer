<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\ObjectPropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Object;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

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
    public function testCreate(): void
    {
        $objectType = new ObjectType(Car::class);
        $property1 = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $property2 = $this->getMockBuilder(ObjectPropertyMetaDataInterface::class)->getMock();
        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('getPropertiesMetadata')
            ->willReturn(['title' => $property1, 'radio' => $property2]);

        $metaDataReader = $this->getMockBuilder(ReaderInterface::class)->getMock();
        $metaDataReader->expects(self::once())
            ->method('read')
            ->with(Car::class)
            ->willReturn($metaData);

        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::exactly(2))
            ->method('createFromMetaData')
            ->withConsecutive($property1, $property2)
            ->willReturn($processor);

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
        $nonObjectType = $this->getMockBuilder(TypeInterface::class)->getMock();
        $metaDataReader = $this->getMockBuilder(ReaderInterface::class)->getMock();
        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();

        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $metaDataReader ReaderInterface */
        $objectMember = new ObjectMember($processorFactory, $metaDataReader);
        /* @var  $nonObjectType TypeInterface */
        $shouldBeNull = $objectMember->create($nonObjectType);

        self::assertNull($shouldBeNull);
    }
}
