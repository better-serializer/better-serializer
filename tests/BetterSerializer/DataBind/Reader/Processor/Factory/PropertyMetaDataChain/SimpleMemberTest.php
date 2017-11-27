<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Property;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class SimpleMemberTest extends TestCase
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
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

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

        $simpleMember = new SimpleMember($converterFactory, $injectorFactory);
        $processor = $simpleMember->create($propertyMetaData);

        self::assertInstanceOf(Property::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);

        $simpleMember = new SimpleMember($converterFactory, $injectorFactory);
        $shouldBeNull = $simpleMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
