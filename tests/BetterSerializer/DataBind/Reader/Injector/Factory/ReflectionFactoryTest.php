<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewInjector(): void
    {
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $propertyMetadataStub = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetadataStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        $factory = new ReflectionFactory();
        $injector = $factory->newInjector($propertyMetadataStub);

        self::assertInstanceOf(ReflectionInjector::class, $injector);
    }
}
