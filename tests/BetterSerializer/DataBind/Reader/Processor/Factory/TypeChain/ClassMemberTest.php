<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ClassPropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorResultInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ClassProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ClassMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreate(): void
    {
        $objectType = new ClassType(Car::class);
        $property1 = $this->createMock(PropertyMetaDataInterface::class);
        $property2 = $this->createMock(ClassPropertyMetaDataInterface::class);
        $metaData = $this->createMock(MetaDataInterface::class);
        $metaData->expects(self::once())
            ->method('getPropertiesMetadata')
            ->willReturn(['title' => $property1, 'radio' => $property2]);

        $metaDataReader = $this->createMock(ReaderInterface::class);
        $metaDataReader->expects(self::once())
            ->method('read')
            ->with(Car::class)
            ->willReturn($metaData);

        $processor = $this->createMock(ProcessorInterface::class);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::exactly(2))
            ->method('createFromMetaData')
            ->withConsecutive([$property1], [$property2])
            ->willReturn($processor);

        $instantiator = $this->createMock(InstantiatorInterface::class);

        $instantiatorResult = $this->createMock(InstantiatorResultInterface::class);
        $instantiatorResult->expects(self::once())
            ->method('getInstantiator')
            ->willReturn($instantiator);
        $instantiatorResult->expects(self::once())
            ->method('getProcessedMetaData')
            ->willReturn($metaData);

        $instantiatorFactory = $this->createMock(InstantiatorFactoryInterface::class);
        $instantiatorFactory->expects(self::once())
            ->method('newInstantiator')
            ->with($metaData)
            ->willReturn($instantiatorResult);

        $objectMember = new ClassMember($processorFactory, $instantiatorFactory, $metaDataReader);
        $objProcessor = $objectMember->create($objectType);

        self::assertInstanceOf(ClassProcessor::class, $objProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonObjectType = $this->createMock(TypeInterface::class);
        $instantiatorFactory = $this->createMock(InstantiatorFactoryInterface::class);
        $metaDataReader = $this->createMock(ReaderInterface::class);
        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);

        $objectMember = new ClassMember($processorFactory, $instantiatorFactory, $metaDataReader);
        $shouldBeNull = $objectMember->create($nonObjectType);

        self::assertNull($shouldBeNull);
    }
}
