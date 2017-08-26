<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context;

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
        $annotations = [$propertyAnnotation];

        $context = new PropertyContext($reflClass, $reflProperty, $annotations);

        self::assertSame($reflClass, $context->getReflectionClass());
        self::assertSame($reflProperty, $context->getReflectionProperty());
        self::assertSame($annotations, $context->getAnnotations());
        self::assertSame('test', $context->getNamespace());
        self::assertSame($propertyAnnotation, $context->getPropertyAnnotation());
    }
}
