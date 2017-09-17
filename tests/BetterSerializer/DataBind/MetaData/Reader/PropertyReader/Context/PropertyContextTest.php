<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context;

use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Annotations\Property;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyContextTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class PropertyContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn('test');

        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $propertyAnnotation = $this->createMock(PropertyInterface::class);
        $propertyAnnotation->expects(self::once())
            ->method('getAnnotationName')
            ->willReturn(Property::ANNOTATION_NAME);
        $annotations = [$propertyAnnotation];

        $context = new PropertyContext($reflClass, $reflProperty, $annotations);

        self::assertSame($reflClass, $context->getReflectionClass());
        self::assertSame($reflProperty, $context->getReflectionProperty());
        self::assertCount(2, $context->getAnnotations());
        self::assertArrayHasKey(Groups::ANNOTATION_NAME, $context->getAnnotations());
        self::assertInstanceOf(Groups::class, $context->getAnnotations()[Groups::ANNOTATION_NAME]);
        self::assertSame('test', $context->getNamespace());
        self::assertSame($propertyAnnotation, $context->getPropertyAnnotation());
    }

    /**
     *
     */
    public function testEverythingWithEmptyAnnotations(): void
    {
        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn('test');

        $notAnnotation = new class {
        };

        $reflProperty = $this->createMock(ReflectionPropertyInterface::class);
        $annotations = [$notAnnotation];

        $context = new PropertyContext($reflClass, $reflProperty, $annotations);

        self::assertSame($reflClass, $context->getReflectionClass());
        self::assertSame($reflProperty, $context->getReflectionProperty());
        self::assertCount(1, $context->getAnnotations());
        self::assertArrayHasKey(Groups::ANNOTATION_NAME, $context->getAnnotations());
        self::assertInstanceOf(Groups::class, $context->getAnnotations()[Groups::ANNOTATION_NAME]);
        self::assertSame('test', $context->getNamespace());
        self::assertNull($context->getPropertyAnnotation());
    }
}
