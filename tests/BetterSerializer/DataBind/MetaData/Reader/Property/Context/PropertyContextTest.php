<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\Property\Context;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

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
        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn('test');

        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $propertyAnnotation = $this->getMockBuilder(PropertyInterface::class)->getMock();
        $annotations = [$propertyAnnotation];

        /* @var $reflClass ReflectionClass */
        /* @var $reflProperty ReflectionProperty */
        $context = new PropertyContext($reflClass, $reflProperty, $annotations);

        self::assertSame($reflClass, $context->getReflectionClass());
        self::assertSame($reflProperty, $context->getReflectionProperty());
        self::assertSame($annotations, $context->getAnnotations());
        self::assertSame('test', $context->getNamespace());
        self::assertSame($propertyAnnotation, $context->getPropertyAnnotation());
    }
}
