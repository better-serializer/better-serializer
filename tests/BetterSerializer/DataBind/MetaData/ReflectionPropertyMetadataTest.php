<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionProperty;

/**
 * Class PropertyMetadataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionPropertyMetadataTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        /* @var $reflProperty ReflectionProperty */
        $reflProperty = Mockery::mock(ReflectionProperty::class, ['setAccessible' => null]);
        /* @var $type TypeInterface */
        $type = Mockery::mock(TypeInterface::class);

        $metaData = new ReflectionPropertyMetadata($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
    }

    /**
     *
     */
    public function testGetOutputKeyFromReflProperty(): void
    {
        /* @var $reflProperty ReflectionProperty */
        $reflProperty = Mockery::mock(ReflectionProperty::class, ['getName' => 'test', 'setAccessible' => null]);
        /* @var $type TypeInterface */
        $type = Mockery::mock(TypeInterface::class);

        $metaData = new ReflectionPropertyMetadata($reflProperty, [], $type);
        self::assertSame('test', $metaData->getOutputKey());
    }

    /**
     *
     */
    public function testGetOutputKeyFromReflPropertyWhenInvalidPropertyAnnotationProvided(): void
    {
        /* @var $reflProperty ReflectionProperty */
        $reflProperty = Mockery::mock(ReflectionProperty::class, ['getName' => 'test', 'setAccessible' => null]);
        /* @var $type TypeInterface */
        $type = Mockery::mock(TypeInterface::class);
        $propertyAnnotation = Mockery::mock(PropertyInterface::class, ['getName' => '']);

        $metaData = new ReflectionPropertyMetadata($reflProperty, [$propertyAnnotation], $type);
        self::assertSame('test', $metaData->getOutputKey());
    }

    /**
     *
     */
    public function testGetOutputKeyFromPropertyAnnotation(): void
    {
        /* @var $reflProperty ReflectionProperty */
        $reflProperty = Mockery::mock(ReflectionProperty::class, ['getName' => 'test', 'setAccessible' => null]);
        /* @var $type TypeInterface */
        $type = Mockery::mock(TypeInterface::class);
        $propertyAnnotation = Mockery::mock(PropertyInterface::class, ['getName' => 'test2']);

        $metaData = new ReflectionPropertyMetadata($reflProperty, [$propertyAnnotation], $type);
        self::assertSame('test2', $metaData->getOutputKey());
    }
}
