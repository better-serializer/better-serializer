<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use RuntimeException;

/**
 * Class ReflectionFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewExtractor(): void
    {
        /* @var $reflPropertyStub ReflectionProperty */
        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);

        /* @var $propertyMetadataStub PropertyMetaDataInterface */
        $propertyMetadataStub = Mockery::mock(
            ReflectionPropertyMetaDataInterface::class,
            ['getReflectionProperty' => $reflPropertyStub]
        );

        $factory = new ReflectionFactory();
        $extractor = $factory->newExtractor($propertyMetadataStub);

        self::assertInstanceOf(ReflectionExtractor::class, $extractor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Not a ReflectionPropertyMetaDataInterface - [a-zA-Z0-9_]+/
     */
    public function testNewExtractorThrowsException(): void
    {
        /* @var $propertyMetadataStub PropertyMetaDataInterface */
        $propertyMetadataStub = Mockery::mock(PropertyMetaDataInterface::class);

        $factory = new ReflectionFactory();
        $factory->newExtractor($propertyMetadataStub);
    }
}
