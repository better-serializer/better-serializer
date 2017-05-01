<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeFactoryInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class PropertyReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PropertyReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetPropertyMetadataWithoutAnnotations(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);

        $varTagStub = Mockery::mock(DocBlock\Tags\Var_::class)
            ->shouldReceive('getType')
            ->andReturn('string')
            ->mock();

        $docBlockStub = Mockery::mock(new DocBlock())
            ->shouldReceive('getTagsByName')
            ->andReturn([$varTagStub])
            ->mock();

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class)
            ->shouldReceive('create')
            ->andReturnValues([
                $docBlockStub,
                $docBlockStub
            ])
            ->mock();
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andReturnValues([
                new NullType(),
                new StringType(),
                new NullType(),
                new StringType()
            ])
            ->mock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);
        $reflPropertyStub1 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDocComment' => '/** @var string  */']
        );
        $reflPropertyStub2 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property2', 'getDocComment' => '/** @var string  */']
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
    public function testGetPropertyMetadataWithAnnotations(): void
    {
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class, ['getType' => 'string']);
        $propertyAnnotStub2 = Mockery::mock(PropertyInterface::class, ['getType' => 'string']);

        /* @var $annotationReaderStub Mockery\Mock */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class);
        $annotationReaderStub->shouldReceive('getPropertyAnnotations')
            ->andReturnValues([
                [$propertyAnnotStub1],
                [$propertyAnnotStub2]
            ])
            ->mock();
        /* @var $annotationReaderStub AnnotationReader */

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andReturnValues([
                new StringType(),
                new StringType()
            ])
            ->mock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);
        $reflPropertyStub1 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDocComment' => '']
        );
        $reflPropertyStub2 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property2', 'getDocComment' => '']
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
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class, ['getType' => 'string']);

        /* @var $annotationReaderStub Mockery\Mock */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class);
        $annotationReaderStub->shouldReceive('getPropertyAnnotations')
            ->andReturnValues([
                [$propertyAnnotStub1],
                []
            ])
            ->mock();
        /* @var $annotationReaderStub AnnotationReader */

        $varTagStub = Mockery::mock(DocBlock\Tags\Var_::class)
            ->shouldReceive('getType')
            ->andReturn('string')
            ->mock();

        $docBlockStub = Mockery::mock(new DocBlock())
            ->shouldReceive('getTagsByName')
            ->andReturn([$varTagStub])
            ->mock();

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class, ['create' => $docBlockStub]);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andReturnValues([
                new StringType(),
                new NullType(),
                new StringType()
            ])
            ->mock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);
        $reflPropertyStub1 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDocComment' => '']
        );
        $reflPropertyStub2 = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property2', 'getDocComment' => '/** @var string  */']
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
     * @expectedException Exception
     * @expectedExceptionMessageRegExp /You need to add a docblock to property "[a-zA-Z0-9]+"/
     */
    public function testGetPropertyMetadataWithoutDocblock(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andReturnValues([
                new NullType()
            ])
            ->mock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);
        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDocComment' => '']
        );
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub]]
        );
        /* @var $reflClassStub ReflectionClass */
        $reader->getPropertyMetadata($reflClassStub);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessageRegExp /You need to add an @var annotation to property "[a-zA-Z0-9]+"/
     */
    public function testGetPropertyMetadataWithoutVarTag(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);

        $docBlockStub = Mockery::mock(new DocBlock(), ['getTagsByName' => []]);

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class, ['create' => $docBlockStub]);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andReturnValues([
                new NullType()
            ])
            ->mock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);
        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDocComment' => '/** @global */']
        );
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub]]
        );
        /* @var $reflClassStub ReflectionClass */
        $reader->getPropertyMetadata($reflClassStub);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9]+, property: [a-zA-Z0-9]+/
     */
    public function testGetPropertyMetadataWithMixedVarTag(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);

        $varTagStub = Mockery::mock(DocBlock\Tags\Var_::class)
            ->shouldReceive('getType')
            ->andReturn('mixed')
            ->mock();

        $docBlockStub = Mockery::mock(new DocBlock(), ['getTagsByName' => [$varTagStub]]);

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class, ['create' => $docBlockStub]);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andReturnValues([
                new NullType(),
                new NullType()
            ])
            ->mock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);

        $reflClassHelperStub = Mockery::mock(
            ReflectionClass::class,
            ['getName' => 'TestClass']
        );

        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            [
                'getName' => 'property1',
                'getDocComment' => '/** @var mixed */',
                'getDeclaringClass' => $reflClassHelperStub
            ]
        );
        $reflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getProperties' => [$reflPropertyStub]]
        );
        /* @var $reflClassStub ReflectionClass */
        $reader->getPropertyMetadata($reflClassStub);
    }
}
