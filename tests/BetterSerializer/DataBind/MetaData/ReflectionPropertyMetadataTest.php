<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
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
        $reflProperty = Mockery::mock(ReflectionProperty::class);
        /* @var $type TypeInterface */
        $type = Mockery::mock(TypeInterface::class);

        $metaData = new ReflectionPropertyMetadata($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertFalse($metaData->isObject());
    }

    /**
     *
     */
    public function testGetTypeObject(): void
    {
        /* @var $reflProperty ReflectionProperty */
        $reflProperty = Mockery::mock(ReflectionProperty::class);
        $type = new ObjectType('asd');

        /* @var $type TypeInterface */
        $metaData = new ReflectionPropertyMetadata($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertTrue($metaData->isObject());
    }
}
