<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * Class ObjectPropertyMetadataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ObjectPropertyMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = new ObjectType(Car::class);

        /* @var $reflProperty ReflectionProperty */
        $metaData = new ObjectPropertyMetaData($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertInstanceOf(ObjectType::class, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertSame(Car::class, $metaData->getObjectClass());
    }
}
