<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\PropertyMetadata;
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
                $docBlockStub,
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
                new StringType(),
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
        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property2']);
    }

//    /**
//     *
//     */
//    public function testGetPropertyMetadataTypesFromDocblock(): void
//    {
//        /* @var $annotationReaderStub AnnotationReader */
//        $annotationReaderStub = Mockery::mock(AnnotationReader::class, ['getPropertyAnnotations' => []]);
//        /* @var $docBlockFactoryStub DocBlockFactoryInterface */
//        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class);
//        /* @var $typeFactoryStub TypeFactoryInterface */
//        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
//
//        $reader = new PropertyReader($annotationReaderStub, $docBlockFactoryStub, $typeFactoryStub);
//        $reflPropertyStub1 = Mockery::mock(ReflectionProperty::class, ['getName' => 'property1']);
//        $reflPropertyStub2 = Mockery::mock(ReflectionProperty::class, ['getName' => 'property2']);
//        $reflClassStub = Mockery::mock(
//            ReflectionClass::class,
//            ['getProperties' => [$reflPropertyStub1, $reflPropertyStub2]]
//        );
//        /* @var $reflClassStub ReflectionClass */
//        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);
//
//        self::assertInternalType('array', $propertyMetadata);
//        self::assertCount(2, $propertyMetadata);
//        self::assertArrayHasKey('property1', $propertyMetadata);
//        self::assertArrayHasKey('property2', $propertyMetadata);
//        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property1']);
//        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property2']);
//    }
}
