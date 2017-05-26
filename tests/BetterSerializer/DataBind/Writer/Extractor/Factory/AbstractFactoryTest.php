<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use BetterSerializer\Helper\DataBind\MetaData\FakePropertyMetaData;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionProperty;
use RuntimeException;

/**
 * Class AbstractFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AbstractFactoryTest extends TestCase
{

    /**
     * @dataProvider classMappingProvider
     * @param PropertyMetaDataInterface $propertyMetadata
     * @param string $propExtractorClass
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
     */
    public function classMappingProvider(): array
    {
        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);
        $reflPropertyMetadata = Mockery::mock(
            ReflectionPropertyMetaDataInterface::class,
            ['getReflectionProperty' => $reflPropertyStub]
        );

        return [
            [$reflPropertyMetadata, ReflectionExtractor::class]
        ];
    }
}
