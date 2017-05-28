<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class AnnotationPropertyTypeReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AnnotationPropertyTypeReaderTest extends TestCase
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
    public function testGetTypeWithoutAnnotations(): void
    {
        /* @var $context Mockery\MockInterface */
        $context = Mockery::mock(PropertyContextInterface::class);
        $context->shouldReceive('getPropertyAnnotation')
            ->once()
            ->andReturn(null)
            ->getMock();
        /* @var $context PropertyContextInterface */

        $reader = new AnnotationPropertyTypeReader();
        $typedContext = $reader->resolveType($context);

        self::assertNull($typedContext);
    }

    /**
     *
     */
    public function testGetTypeWithAnnotations(): void
    {
        $propertyAnnotStub1 = Mockery::mock(PropertyInterface::class, ['getType' => 'string']);

        /* @var $context Mockery\MockInterface */
        $context = Mockery::mock(PropertyContextInterface::class);
        $context->shouldReceive('getPropertyAnnotation')
            ->once()
            ->andReturn($propertyAnnotStub1)
            ->getMock();
        /* @var $context PropertyContextInterface */

        $reader = new AnnotationPropertyTypeReader();
        $typedContext = $reader->resolveType($context);

        self::assertInstanceOf(StringTypedPropertyContext::class, $typedContext);
        self::assertSame('string', $typedContext->getStringType());
    }
}
