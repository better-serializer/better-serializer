<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\PropertyMetadata;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class PropertyReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class PropertyReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetPropertyMetadata(): void
    {
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);
        /* @var $annotationReaderStub AnnotationReader */
        $reader = new PropertyReader($annotationReaderStub);
        $reflPropertyStub1 = Mockery::mock(ReflectionProperty::class, ['getName' => 'property1']);
        $reflPropertyStub2 = Mockery::mock(ReflectionProperty::class, ['getName' => 'property2']);
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub1, $reflPropertyStub2]]
        );
        /* @var $reflClassStub ReflectionClass */
        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(2, $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property2']);
    }
}
