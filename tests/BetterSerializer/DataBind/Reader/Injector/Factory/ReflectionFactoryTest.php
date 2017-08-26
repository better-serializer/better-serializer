<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
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
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $propertyMetadataStub = $this->createMock(ReflectionPropertyMetaDataInterface::class);
        $propertyMetadataStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

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
        $propertyMetadataStub = $this->createMock(PropertyMetaDataInterface::class);

        $factory = new ReflectionFactory();
        $factory->newInjector($propertyMetadataStub);
    }
}
