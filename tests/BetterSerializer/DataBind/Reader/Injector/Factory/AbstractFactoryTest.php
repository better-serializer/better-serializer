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
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AbstractFactoryTest extends TestCase
{

    /**
     * @dataProvider classMappingProvider
     * @param PropertyMetaDataInterface $propertyMetadata
     * @param string $propInjectorClass
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNewInjector(PropertyMetaDataInterface $propertyMetadata, string $propInjectorClass): void
    {
        $factory = new AbstractFactory();
        $injector = $factory->newInjector($propertyMetadata);

        self::assertInstanceOf($propInjectorClass, $injector);
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     */
    public function classMappingProvider(): array
    {
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyMetadata = $this->createMock(PropertyMetaDataInterface::class);
        $reflPropertyMetadata->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        return [
            [$reflPropertyMetadata, ReflectionInjector::class]
        ];
    }
}
