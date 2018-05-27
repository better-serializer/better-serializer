<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\SimplePropertyProcessor;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class SimplePropertyMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreate(): void
    {
        $type = new StringType();
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);

        $injector = $this->createMock(InjectorInterface::class);

        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);
        $injectorFactory->expects(self::once())
            ->method('newInjector')
            ->willReturn($injector);

        $converter = $this->createMock(ConverterInterface::class);

        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->willReturn($converter);

        $nameTranslator = $this->createMock(TranslatorInterface::class);
        $nameTranslator->expects(self::once())
            ->method('translate')
            ->with($propertyMetaData)
            ->willReturn('test');

        $simpleMember = new SimplePropertyMember($converterFactory, $injectorFactory, $nameTranslator);
        $processor = $simpleMember->create($propertyMetaData);

        self::assertInstanceOf(SimplePropertyProcessor::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new ClassType(Car::class);
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $nameTranslator = $this->createMock(TranslatorInterface::class);

        $simpleMember = new SimplePropertyMember($converterFactory, $injectorFactory, $nameTranslator);
        $shouldBeNull = $simpleMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
