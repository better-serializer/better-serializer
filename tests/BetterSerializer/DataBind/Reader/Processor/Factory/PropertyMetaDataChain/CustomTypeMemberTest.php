<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\CustomTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Processor\CustomType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Helper\CustomTypeMockFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class CustomTypeMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testCreate(): void
    {
        $className = Car::class;
        $customHandler = CustomTypeMockFactory::createCustomTypeExcensionMock($className);
        $handlerClass = get_class($customHandler);

        $injector = $this->createMock(InjectorInterface::class);

        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);
        $injectorFactory->expects(self::once())
            ->method('newInjector')
            ->willReturn($injector);

        $parameters = $this->createMock(ParametersInterface::class);
        $type = $this->createMock(CustomTypeInterface::class);
        $type->expects(self::exactly(2))
            ->method('getCustomType')
            ->willReturn($className);
        $type->expects(self::once())
            ->method('getParameters')
            ->willReturn($parameters);

        $metaData = $this->createMock(PropertyMetaDataInterface::class);
        $metaData->expects(self::exactly(2))
            ->method('getType')
            ->willReturn($type);

        $customObjectMember = new CustomTypeMember($injectorFactory, [$handlerClass]);
        $processor = $customObjectMember->create($metaData);

        self::assertInstanceOf(CustomType::class, $processor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);

        new CustomTypeMember($injectorFactory, [Car::class]);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Handler for class [a-zA-Z0-9_\\]+ is already registered\./
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testCreateThrowsOnDuplicitHandlerRegistration(): void
    {
        $className = Car::class;
        $customHandler1 = CustomTypeMockFactory::createCustomTypeExcensionMock($className);
        $handlerClass1 = get_class($customHandler1);
        $customHandler2 = CustomTypeMockFactory::createCustomTypeExcensionMock($className);
        $handlerClass2 = get_class($customHandler2);

        $injectorFactory = $this->createMock(InjectorFactoryInterface::class);

        $member = new CustomTypeMember($injectorFactory, [$handlerClass1]);
        $member->addCustomHandlerClass($handlerClass2);
    }
}
