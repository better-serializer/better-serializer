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
 * ClassModel PropertyMetadataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionPropertyMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testBasicFunctions(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('setAccessible');

        $groups = [Groups::DEFAULT_GROUP];
        $groupsAnnotation = $this->createMock(GroupsInterface::class);
        $groupsAnnotation->expects(self::once())
            ->method('getGroups')
            ->willReturn($groups);

        $type = $this->createMock(TypeInterface::class);

        $metaData = new ReflectionPropertyMetadata(
            $reflProperty,
            [Groups::ANNOTATION_NAME => $groupsAnnotation],
            $type
        );
        self::assertSame($type, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
        self::assertSame($groups, $metaData->getGroups());
    }

    /**
     *
     */
    public function testGetOutputKeyFromReflProperty(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('getName')
            ->willReturn('test');
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $groupsAnnotation = $this->createMock(GroupsInterface::class);
        $type = $this->createMock(TypeInterface::class);

        $metaData = new ReflectionPropertyMetadata(
            $reflProperty,
            [Groups::ANNOTATION_NAME => $groupsAnnotation],
            $type
        );
        self::assertSame('test', $metaData->getOutputKey());
    }

    /**
     *
     */
    public function testGetOutputKeyFromReflPropertyWhenInvalidPropertyAnnotationProvided(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::once())
            ->method('getName')
            ->willReturn('test');
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = $this->createMock(TypeInterface::class);
        $propertyAnnotation = $this->createMock(PropertyInterface::class);
        $propertyAnnotation->expects(self::once())
            ->method('getName')
            ->willReturn('');
        $groupsAnnotation = $this->createMock(GroupsInterface::class);

        $metaData = new ReflectionPropertyMetadata(
            $reflProperty,
            [
                Groups::ANNOTATION_NAME => $groupsAnnotation,
                Property::ANNOTATION_NAME => $propertyAnnotation,
            ],
            $type
        );
        self::assertSame('test', $metaData->getOutputKey());
    }

    /**
     *
     */
    public function testGetOutputKeyFromPropertyAnnotation(): void
    {
        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $reflProperty->expects(self::exactly(0))
            ->method('getName');
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = $this->createMock(TypeInterface::class);
        $propertyAnnotation = $this->createMock(PropertyInterface::class);
        $propertyAnnotation->expects(self::once())
            ->method('getName')
            ->willReturn('test2');
        $groupsAnnotation = $this->createMock(GroupsInterface::class);

        $metaData = new ReflectionPropertyMetadata(
            $reflProperty,
            [
                Groups::ANNOTATION_NAME => $groupsAnnotation,
                Property::ANNOTATION_NAME => $propertyAnnotation,
            ],
            $type
        );
        self::assertSame('test2', $metaData->getOutputKey());
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

        new ReflectionPropertyMetadata($reflProperty, [], $type);
    }
}
