<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Reader\Property\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\Property\Context\StringTypedPropertyContext;
use PHPUnit\Framework\TestCase;

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
    public function testGetTypeWithoutAnnotations(): void
    {
        $context = $this->getMockBuilder(PropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getPropertyAnnotation')
            ->willReturn(null);
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
        $propertyAnnotStub1 = $this->getMockBuilder(PropertyInterface::class)->getMock();
        $propertyAnnotStub1->expects(self::once())
            ->method('getType')
            ->willReturn('string');

        $context = $this->getMockBuilder(PropertyContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getPropertyAnnotation')
            ->willReturn($propertyAnnotStub1);
        /* @var $context PropertyContextInterface */

        $reader = new AnnotationPropertyTypeReader();
        $typedContext = $reader->resolveType($context);

        self::assertInstanceOf(StringTypedPropertyContext::class, $typedContext);
        self::assertSame('string', $typedContext->getStringType());
    }
}
