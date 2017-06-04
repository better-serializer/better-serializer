<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Property;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;

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
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn('test');

        $injector = $this->getMockBuilder(InjectorInterface::class)->getMock();

        $injectorFactory = $this->getMockBuilder(InjectorFactoryInterface::class)->getMock();
        $injectorFactory->expects(self::once())
            ->method('newInjector')
            ->willReturn($injector);

        /* @var $injectorFactory InjectorFactoryInterface */
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $simpleMember = new SimpleMember($injectorFactory);
        $processor = $simpleMember->create($propertyMetaData);

        self::assertInstanceOf(Property::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $injectorFactory = Mockery::mock(InjectorFactoryInterface::class);

        /* @var $injectorFactory InjectorFactoryInterface */
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $simpleMember = new SimpleMember($injectorFactory);
        $shouldBeNull = $simpleMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}
