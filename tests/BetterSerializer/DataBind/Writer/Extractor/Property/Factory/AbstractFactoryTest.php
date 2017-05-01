<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetadataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use BetterSerializer\Helper\DataBind\MetaData\FakePropertyMetadata;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionProperty;
use RuntimeException;

/**
 * Class AbstractFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Property\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AbstractFactoryTest extends TestCase
{

    /**
     * @dataProvider classMappingProvider
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string $propExtractorClass
     */
    public function testNewExtractor(PropertyMetadataInterface $propertyMetadata, string $propExtractorClass): void
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
        $factory->newExtractor(new FakePropertyMetadata());
    }

    /**
     * @return array
     */
    public function classMappingProvider(): array
    {
        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);
        $reflPropertyMetadata = Mockery::mock(
            ReflectionPropertyMetadataInterface::class,
            ['getReflectionProperty' => $reflPropertyStub]
        );

        return [
            [$reflPropertyMetadata, ReflectionExtractor::class]
        ];
    }
}
