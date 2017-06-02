<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;
use Mockery;
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
        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);

        /* @var $propertyMetadataStub PropertyMetaDataInterface */
        $propertyMetadataStub = Mockery::mock(
            ReflectionPropertyMetaDataInterface::class,
            ['getReflectionProperty' => $reflPropertyStub]
        );

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
        $propertyMetadataStub = Mockery::mock(PropertyMetaDataInterface::class);

        $factory = new ReflectionFactory();
        $factory->newInjector($propertyMetadataStub);
    }
}
