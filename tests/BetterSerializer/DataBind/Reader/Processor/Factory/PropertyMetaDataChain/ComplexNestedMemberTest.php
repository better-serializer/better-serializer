<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexNestedProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexNested;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use LogicException;

/**
 * Class ObjectMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ComplexNestedMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreate(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

        $objProcessor = $this->getMockBuilder(ComplexNestedProcessorInterface::class)->getMock();

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
        $complexNestedMember = new ComplexNestedMember($processorFactory, $injectorFactory);
        $processor = $complexNestedMember->create($propertyMetaData);

        self::assertInstanceOf(ComplexNested::class, $processor);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Invalid processor type: '[a-zA-Z0-9_]+'/
     */
    public function testCreateThrowsException(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);

        $objProcessor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

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
        $complexNestedMember = new ComplexNestedMember($processorFactory, $injectorFactory);
        $complexNestedMember->create($propertyMetaData);
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
        $complexNestedMember = new ComplexNestedMember($processorFactory, $injectorFactory);
        $shouldBeNull = $complexNestedMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
