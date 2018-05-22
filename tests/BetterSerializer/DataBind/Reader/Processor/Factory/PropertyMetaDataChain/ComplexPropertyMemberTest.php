<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\PropertyProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexPropertyProcessor;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
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
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);

        $objProcessor = $this->createMock(PropertyProcessorInterface::class);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($objProcessor);

        $injector = $this->createMock(InjectorInterface::class);

        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);
        $injectorFactory->expects(self::once())
            ->method('newInjector')
            ->willReturn($injector);

        $nameTranslator = $this->createMock(TranslatorInterface::class);
        $nameTranslator->expects(self::once())
            ->method('translate')
            ->with($propertyMetaData)
            ->willReturn('testName');

        $complexNestedMember = new ComplexPropertyMember($processorFactory, $injectorFactory, $nameTranslator);
        $processor = $complexNestedMember->create($propertyMetaData);

        self::assertInstanceOf(ComplexPropertyProcessor::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new StringType();
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);
        $nameTranslator = $this->createMock(TranslatorInterface::class);

        $complexNestedMember = new ComplexPropertyMember($processorFactory, $injectorFactory, $nameTranslator);
        $shouldBeNull = $complexNestedMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
