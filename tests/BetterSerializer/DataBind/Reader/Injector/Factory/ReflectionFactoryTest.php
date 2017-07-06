<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use RuntimeException;

/**
 * Class ReflectionFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewInjector(): void
    {
        /* @var $reflPropertyStub ReflectionProperty */
        $reflPropertyStub = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $propertyMetadataStub = $this->getMockBuilder(ReflectionPropertyMetaDataInterface::class)->getMock();
        $propertyMetadataStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        /* @var $propertyMetadataStub PropertyMetaDataInterface */
        $factory = new ReflectionFactory();
        $injector = $factory->newInjector($propertyMetadataStub);

        self::assertInstanceOf(ReflectionInjector::class, $injector);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Not a ReflectionPropertyMetaDataInterface - [a-zA-Z0-9_]+/
     */
    public function testNewInjectorThrowsException(): void
    {
        /* @var $propertyMetadataStub PropertyMetaDataInterface */
        $propertyMetadataStub = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        $factory = new ReflectionFactory();
        $factory->newInjector($propertyMetadataStub);
    }
}
