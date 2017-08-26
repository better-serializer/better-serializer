<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;
use BetterSerializer\Helper\DataBind\MetaData\FakePropertyMetaData;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class AbstractFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AbstractFactoryTest extends TestCase
{

    /**
     * @dataProvider classMappingProvider
     * @param PropertyMetaDataInterface $propertyMetadata
     * @param string $propInjectorClass
     */
    public function testNewInjector(PropertyMetaDataInterface $propertyMetadata, string $propInjectorClass): void
    {
        $factory = new AbstractFactory();
        $injector = $factory->newInjector($propertyMetadata);

        self::assertInstanceOf($propInjectorClass, $injector);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unexpected class: [A-Z][a-zA-Z0-9_]+/
     */
    public function testNewInjectorThrowsException(): void
    {
        $factory = new AbstractFactory();
        $factory->newInjector(new FakePropertyMetaData());
    }

    /**
     * @return array
     */
    public function classMappingProvider(): array
    {
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyMetadata = $this->createMock(ReflectionPropertyMetaDataInterface::class);
        $reflPropertyMetadata->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        return [
            [$reflPropertyMetadata, ReflectionInjector::class]
        ];
    }
}
