<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;

/**
 * Class PropertyReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PropertyReaderTest extends TestCase
{

    /**
     *
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Type readers missing.
     */
    public function testConstructionWithEmptyTypeReadersThrowsException(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class);
        $typeFactoryStup = Mockery::mock(TypeFactoryInterface::class);

        new PropertyReader(
            $annotationReaderStub,
            $typeFactoryStup,
            []
        );
    }

    /**
     *
     */
    public function testGetPropertyMetadata(): void
    {
        $typedContextStub = Mockery::mock(StringTypedPropertyContextInterface::class);

        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);

        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class)
            ->shouldReceive('getType')
            ->twice()
            ->andReturn(new StringType())
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $typeReaderStub = Mockery::mock(TypeReaderInterface::class)
            ->shouldReceive('resolveType')
            ->twice()
            ->andReturn($typedContextStub)
            ->getMock();
        /* @var $typeReaderStub TypeReaderInterface */

        $reflPropertyStub1 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'setAccessible' => null]
        );
        $reflPropertyStub2 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property2', 'setAccessible' => null]
        );
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub1, $reflPropertyStub2]]
        );
        /* @var $reflClassStub ReflectionClass */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $typeFactoryStub,
            [$typeReaderStub]
        );
        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(2, $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property2']);
        self::assertInstanceOf(StringType::class, $propertyMetadata['property1']->getType());
        self::assertInstanceOf(StringType::class, $propertyMetadata['property2']->getType());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9\\]+, property: [a-zA-Z0-9]+/
     */
    public function testGetPropertyMetadataThrowsException(): void
    {
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class);
        $propertyAnnotStub2 = Mockery::mock(PropertyInterface::class);

        /* @var $annotationReaderStub Mockery\Mock */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class);
        $annotationReaderStub->shouldReceive('getPropertyAnnotations')
            ->once()
            ->andReturnValues([
                [$propertyAnnotStub1],
                [$propertyAnnotStub2]
            ])
            ->getMock();
        /* @var $annotationReaderStub AnnotationReader */

        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class)
            ->shouldReceive('getType')
            ->times(0)
            ->andReturn(new StringType())
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $typeReaderStub = Mockery::mock(TypeReaderInterface::class)
            ->shouldReceive('resolveType')
            ->once()
            ->andReturn(null)
            ->getMock();
        /* @var $typeReaderStub TypeReaderInterface */

        $reflDeclaringClassStub = Mockery::mock(ReflectionClass::class);
        $reflDeclaringClassStub->shouldReceive('getName')
            ->once()
            ->andReturn(StringType::class);

        $reflPropertyStub1 = Mockery::mock(ReflectionProperty::class);
        $reflPropertyStub1->shouldReceive('getName')
            ->twice()
            ->andReturn('property1')
            ->getMock()
            ->shouldReceive('getDeclaringClass')
            ->once()
            ->andReturn($reflDeclaringClassStub)
            ->getMock();

        $reflPropertyStub2 = Mockery::mock(ReflectionProperty::class);
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub1, $reflPropertyStub2]]
        );
        /* @var $reflClassStub ReflectionClass */

        $reader = new PropertyReader($annotationReaderStub, $typeFactoryStub, [$typeReaderStub]);
        $reader->getPropertyMetadata($reflClassStub);
    }
}
