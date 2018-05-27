<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
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
            [$reflPropertyMetadata, ReflectionExtractor::class]
        ];
    }
}
