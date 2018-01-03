<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\PropertyProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexPropertyProcessor;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
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
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

        $objProcessor = $this->getMockBuilder(PropertyProcessorInterface::class)->getMock();

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($objProcessor);

        $injector = $this->getMockBuilder(InjectorInterface::class)->getMock();

        $injectorFactory = $this->getMockBuilder(InjectorFactoryInterface::class)->getMock();
        $injectorFactory->expects(self::once())
            ->method('newInjector')
            ->willReturn($injector);

        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $injectorFactory InjectorFactoryInterface */
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $complexNestedMember = new ComplexPropertyMember($processorFactory, $injectorFactory);
        $processor = $complexNestedMember->create($propertyMetaData);

        self::assertInstanceOf(ComplexPropertyProcessor::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new StringType();
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $injectorFactory = $this->getMockBuilder(InjectorFactoryInterface::class)->getMock();

        /* @var $processorFactory ProcessorFactoryInterface */
        /* @var $injectorFactory InjectorFactoryInterface */
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $complexNestedMember = new ComplexPropertyMember($processorFactory, $injectorFactory);
        $shouldBeNull = $complexNestedMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
