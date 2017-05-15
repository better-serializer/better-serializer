<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;
use ReflectionProperty;
use LogicException;
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
     *
     */
    public function testGetPropertyMetadataWithoutAnnotations(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);
        $annotationPropertyTypeReaderStub = Mockery::mock(AnnotationPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->twice()
            ->with([])
            ->andThrow(RuntimeException::class, 'Property annotation missing.')
            ->getMock();
        /* @var $annotationPropertyTypeReaderStub AnnotationPropertyTypeReaderInterface */

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

        $docBlockPropertyTypeReaderStub = Mockery::mock(DocBlockPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->with($reflPropertyStub1)
            ->andReturn(new StringType())
            ->getMock()
            ->shouldReceive('getType')
            ->once()
            ->with($reflPropertyStub2)
            ->andReturn(new StringType())
            ->getMock();
        /* @var $docBlockPropertyTypeReaderStub DocBlockPropertyTypeReaderInterface */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $annotationPropertyTypeReaderStub,
            $docBlockPropertyTypeReaderStub
        );
        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(2, $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property2']);
    }

    /**
     *
     */
    public function testGetPropertyMetadataWithAnnotations(): void
    {
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class);
        $propertyAnnotStub2 = Mockery::mock(PropertyInterface::class);

        /* @var $annotationReaderStub Mockery\Mock */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class);
        $annotationReaderStub->shouldReceive('getPropertyAnnotations')
            ->twice()
            ->andReturnValues([
                [$propertyAnnotStub1],
                [$propertyAnnotStub2]
            ])
            ->getMock();
        /* @var $annotationReaderStub AnnotationReader */

        $annotationPropertyTypeReaderStub = Mockery::mock(AnnotationPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->with([$propertyAnnotStub1])
            ->andReturn(new StringType())
            ->getMock()
            ->shouldReceive('getType')
            ->once()
            ->with([$propertyAnnotStub2])
            ->andReturn(new StringType())
            ->getMock();
        /* @var $annotationPropertyTypeReaderStub AnnotationPropertyTypeReaderInterface */

        $docBlockPropertyTypeReaderStub = Mockery::mock(DocBlockPropertyTypeReaderInterface::class);
        /* @var $docBlockPropertyTypeReaderStub DocBlockPropertyTypeReaderInterface */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $annotationPropertyTypeReaderStub,
            $docBlockPropertyTypeReaderStub
        );
        $reflPropertyStub1 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'setAccessible' => null,]
        );
        $reflPropertyStub2 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property2', 'setAccessible' => null,]
        );
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
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property2']);
    }

    /**
     *
     */
    public function testGetPropertyMetadataWithAnnotationsAndDocBlock(): void
    {
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class);

        /* @var $annotationReaderStub Mockery\Mock */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class);
        $annotationReaderStub->shouldReceive('getPropertyAnnotations')
            ->andReturnValues([
                [$propertyAnnotStub1],
                []
            ])
            ->getMock();
        /* @var $annotationReaderStub AnnotationReader */

        $annotationPropertyTypeReaderStub = Mockery::mock(AnnotationPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->with([$propertyAnnotStub1])
            ->andReturn(new StringType())
            ->getMock()
            ->shouldReceive('getType')
            ->once()
            ->with([])
            ->andThrow(RuntimeException::class, 'Property annotation missing.')
            ->getMock();
        /* @var $annotationPropertyTypeReaderStub AnnotationPropertyTypeReaderInterface */

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

        $docBlockPropertyTypeReaderStub = Mockery::mock(DocBlockPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->with($reflPropertyStub2)
            ->andReturn(new StringType())
            ->getMock();
        /* @var $docBlockPropertyTypeReaderStub DocBlockPropertyTypeReaderInterface */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $annotationPropertyTypeReaderStub,
            $docBlockPropertyTypeReaderStub
        );

        /* @var $reflClassStub ReflectionClass */
        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(2, $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property2']);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9]+, property: [a-zA-Z0-9]+/
     */
    public function testGetPropertyMetadataWithoutDocblock(): void
    {
        $this->runPropertyReaderExceptionTest(
            RuntimeException::class,
            'You need to add a docblock to property "property1"'
        );
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9]+, property: [a-zA-Z0-9]+/
     */
    public function testGetPropertyMetadataWithoutVarTag(): void
    {
        $this->runPropertyReaderExceptionTest(
            RuntimeException::class,
            'You need to add an @var annotation to property "propoerty1"'
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - 'mixed'/
     */
    public function testGetPropertyMetadataWithMixedVarTag(): void
    {
        $this->runPropertyReaderExceptionTest(
            LogicException::class,
            "Unknown type - 'mixed'"
        );
    }

    /**
     * @param string $exceptionClass
     * @param string $exceptionMsg
     */
    private function runPropertyReaderExceptionTest(string $exceptionClass, string $exceptionMsg): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);

        $annotationPropertyTypeReaderStub = Mockery::mock(AnnotationPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->with([])
            ->andThrow(RuntimeException::class, 'Property annotation missing.')
            ->getMock();
        /* @var $annotationPropertyTypeReaderStub AnnotationPropertyTypeReaderInterface */

        $declaringReflClassStub = Mockery::mock(ReflectionClass::class, ['getName' => 'TestClass']);

        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDeclaringClass' => $declaringReflClassStub]
        );
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub]]
        );

        $docBlockPropertyTypeReaderStub = Mockery::mock(DocBlockPropertyTypeReaderInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->with($reflPropertyStub)
            ->andThrow($exceptionClass, $exceptionMsg)
            ->getMock();
        /* @var $docBlockPropertyTypeReaderStub DocBlockPropertyTypeReaderInterface */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $annotationPropertyTypeReaderStub,
            $docBlockPropertyTypeReaderStub
        );

        /* @var $reflClassStub ReflectionClass */
        $reader->getPropertyMetadata($reflClassStub);
    }
}
