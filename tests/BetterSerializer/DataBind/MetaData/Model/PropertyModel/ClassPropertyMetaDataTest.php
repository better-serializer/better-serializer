<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Annotations\GroupsInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ClassPropertyMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');

        $groupsAnnotation = $this->createMock(GroupsInterface::class);
        $annotations = [Groups::ANNOTATION_NAME => $groupsAnnotation];

        $type = new ClassType(Car::class);

        $metaData = new ClassPropertyMetaData($reflProperty, $annotations, $type);
        self::assertSame($type, $metaData->getType());
        self::assertInstanceOf(ClassType::class, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertSame(Car::class, $metaData->getObjectClass());
    }
}
