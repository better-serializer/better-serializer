<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ObjectPropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Object;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
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
        $property1 = $this->createMock(PropertyMetaDataInterface::class);
        $property2 = $this->createMock(ObjectPropertyMetaDataInterface::class);
        $metaData = $this->createMock(MetaDataInterface::class);
        $metaData->expects(self::once())
            ->method('getPropertiesMetadata')
            ->willReturn(['title' => $property1, 'radio' => $property2]);

        $metaDataReader = $this->createMock(ContextualReaderInterface::class);
        $metaDataReader->expects(self::once())
            ->method('read')
            ->with(Car::class)
            ->willReturn($metaData);

        $processor = $this->createMock(ProcessorInterface::class);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::exactly(2))
            ->method('createFromMetaData')
            ->withConsecutive($property1, $property2)
            ->willReturn($processor);
        $context = $this->createMock(SerializationContextInterface::class);

        $objectMember = new ObjectMember($processorFactory, $metaDataReader);
        $objProcessor = $objectMember->create($objectType, $context);

        self::assertInstanceOf(Object::class, $objProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonObjectType = $this->createMock(TypeInterface::class);
        $metaDataReader = $this->createMock(ContextualReaderInterface::class);
        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $objectMember = new ObjectMember($processorFactory, $metaDataReader);
        $shouldBeNull = $objectMember->create($nonObjectType, $context);

        self::assertNull($shouldBeNull);
    }
}
