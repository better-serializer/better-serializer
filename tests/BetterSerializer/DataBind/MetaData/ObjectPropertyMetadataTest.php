<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionProperty;

/**
 * Class ObjectPropertyMetadataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ObjectPropertyMetadataTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        /* @var $reflProperty ReflectionProperty */
        $reflProperty = Mockery::mock(ReflectionProperty::class, ['setAccessible' => null]);
        $type = new ObjectType(Car::class);

        $metaData = new ObjectPropertyMetadata($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertInstanceOf(ObjectType::class, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertSame(Car::class, $metaData->getObjectClass());
    }
}
