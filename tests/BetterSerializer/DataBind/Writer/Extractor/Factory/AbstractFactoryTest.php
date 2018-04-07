<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\PropertyExtractor;
use BetterSerializer\Helper\DataBind\MetaData\FakePropertyMetaData;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AbstractFactoryTest extends TestCase
{

    /**
     * @dataProvider classMappingProvider
     * @param PropertyMetaDataInterface $propertyMetadata
     * @param string $propExtractorClass
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNewExtractor(PropertyMetaDataInterface $propertyMetadata, string $propExtractorClass): void
    {
        $factory = new AbstractFactory();
        $extractor = $factory->newExtractor($propertyMetadata);

        self::assertInstanceOf($propExtractorClass, $extractor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unexpected class: [A-Z][a-zA-Z0-9_]+/
     */
    public function testNewExtractorThrowsException(): void
    {
        $factory = new AbstractFactory();
        $factory->newExtractor(new FakePropertyMetaData());
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
        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getName')
            ->willReturn('Car');

        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('getName')
            ->willReturn('title');
        $reflProperty->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($reflClass);

        $reflPropertyMetadata = $this->createMock(ReflectionPropertyMetaDataInterface::class);
        $reflPropertyMetadata->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflProperty);

        return [
            [$reflPropertyMetadata, PropertyExtractor::class]
        ];
    }
}
