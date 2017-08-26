<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * ClassModel ObjectPropertyMetadataTest
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
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = new ObjectType(Car::class);

        $metaData = new ObjectPropertyMetaData($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertInstanceOf(ObjectType::class, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertSame(Car::class, $metaData->getObjectClass());
    }
}
