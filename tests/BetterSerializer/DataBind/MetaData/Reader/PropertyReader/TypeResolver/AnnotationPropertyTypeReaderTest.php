<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
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
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);

        $context = $this->createMock(PropertyContextInterface::class);
        $context->expects(self::once())
            ->method('getPropertyAnnotation')
            ->willReturn(null);

        $reader = new AnnotationPropertyTypeResolver($stringTypeParser);
        $typedContext = $reader->resolveType($context);

        self::assertNull($typedContext);
    }

    /**
     *
     */
    public function testGetTypeWithAnnotations(): void
    {
        $propertyType = 'string';
        $propertyAnnotStub1 = $this->createMock(PropertyInterface::class);
        $propertyAnnotStub1->expects(self::once())
            ->method('getType')
            ->willReturn($propertyType);

        $reflClass = $this->createMock(ReflectionClassInterface::class);

        $context = $this->createMock(PropertyContextInterface::class);
        $context->expects(self::once())
            ->method('getPropertyAnnotation')
            ->willReturn($propertyAnnotStub1);
        $context->expects(self::once())
            ->method('getReflectionClass')
            ->willReturn($reflClass);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);

        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringTypeParser->expects(self::once())
            ->method('parseWithParentContext')
            ->with($propertyType, $reflClass)
            ->willReturn($stringType);

        $reader = new AnnotationPropertyTypeResolver($stringTypeParser);
        $typedContext = $reader->resolveType($context);

        self::assertNotNull($typedContext);
        self::assertInstanceOf(ContextStringFormTypeInterface::class, $typedContext);
    }
}
