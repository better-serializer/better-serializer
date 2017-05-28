<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
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
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     */
    public function testEverything(): void
    {
        $reflClass = Mockery::mock(ReflectionClass::class);
        $reflClass->shouldReceive('getNamespaceName')
            ->once()
            ->andReturn('test');

        $reflProperty = Mockery::mock(ReflectionProperty::class);
        $propertyAnnotation = Mockery::mock(PropertyInterface::class);
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
