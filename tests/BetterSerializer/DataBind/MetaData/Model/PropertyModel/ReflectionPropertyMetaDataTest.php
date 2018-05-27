<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Annotations\GroupsInterface;
use BetterSerializer\DataBind\MetaData\Annotations\Property;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ReflectionPropertyMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testBasicFunctions(): void
    {
        $name = 'testName';

        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $reflProperty->expects(self::once())
            ->method('getName')
            ->willReturn($name);

        $groups = [Groups::DEFAULT_GROUP];
        $groupsAnnotation = $this->createMock(GroupsInterface::class);
        $groupsAnnotation->expects(self::once())
            ->method('getGroups')
            ->willReturn($groups);

        $type = $this->createMock(TypeInterface::class);

        $metaData = new PropertyMetadata(
            $reflProperty,
            [Groups::ANNOTATION_NAME => $groupsAnnotation],
            $type
        );
        self::assertSame($type, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertSame($groups, $metaData->getGroups());
        self::assertSame($name, $metaData->getName());
    }

    /**
     *
     */
    public function testGetSerializationName(): void
    {
        $name = 'testName';

        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');

        $groupsAnnotation = $this->createMock(GroupsInterface::class);
        $propertyAnnotation = $this->createMock(PropertyInterface::class);
        $propertyAnnotation->expects(self::once())
            ->method('getName')
            ->willReturn($name);

        $type = $this->createMock(TypeInterface::class);

        $metaData = new PropertyMetadata(
            $reflProperty,
            [
                Groups::ANNOTATION_NAME => $groupsAnnotation,
                Property::ANNOTATION_NAME => $propertyAnnotation
            ],
            $type
        );

        self::assertSame($name, $metaData->getSerializationName());
    }

    /**
     *
     */
    public function testGetSerializationNameWithoutPropertyAnnotation(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');

        $groupsAnnotation = $this->createMock(GroupsInterface::class);
        $type = $this->createMock(TypeInterface::class);

        $metaData = new PropertyMetadata(
            $reflProperty,
            [Groups::ANNOTATION_NAME => $groupsAnnotation,],
            $type
        );

        self::assertSame('', $metaData->getSerializationName());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Groups annotation missing.
     */
    public function testThrowsWhenGroupsAnnotationIsMissing(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');

        $type = $this->createMock(TypeInterface::class);

        new PropertyMetadata($reflProperty, [], $type);
    }
}
