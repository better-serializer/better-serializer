<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class DocBlockPropertyTypeReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetTypeFromVarDocBlock(): void
    {
        $docBlockFactory = DocBlockFactory::createInstance(); // final

        $reflClass = $this->createMock(ReflectionClassInterface::class);

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @var string  */');

        $contextStub = $this->createMock(PropertyContextInterface::class);
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);
        $contextStub->expects(self::once())
            ->method('getReflectionClass')
            ->willReturn($reflClass);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringTypeParser->expects(self::once())
            ->method('parseWithParentContext')
            ->with($this->isType('string'), $reflClass)
            ->willReturn($stringType);

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactory, $stringTypeParser);
        $stringFormType = $typeReader->resolveType($contextStub);

        self::assertNotNull($stringFormType);
        self::assertInstanceOf(ContextStringFormTypeInterface::class, $stringFormType);
        self::assertSame($stringType, $stringFormType);
    }

    /**
     *
     */
    public function testGetTypeWithoutDocBlock(): void
    {
        $docBlockFactoryStub = $this->createMock(DocBlockFactoryInterface::class);

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('');

        $contextStub = $this->createMock(PropertyContextInterface::class);
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactoryStub, $stringTypeParser);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertNull($typedContext);
    }

    /**
     *
     */
    public function testGetTypeWithoutVarTag(): void
    {
        $docBlockFactory = DocBlockFactory::createInstance(); // final

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @global */');

        $contextStub = $this->createMock(PropertyContextInterface::class);
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactory, $stringTypeParser);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertNull($typedContext);
    }
}
