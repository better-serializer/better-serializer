<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringFormTypedPropertyContext;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormType;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class DocBlockPropertyTypeReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
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

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @var string  */');

        $contextStub = $this->createMock(PropertyContextInterface::class);
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactory);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertInstanceOf(ContextStringFormType::class, $typedContext);
        self::assertSame('string', $typedContext->getStringType());
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

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactoryStub);
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

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactory);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertNull($typedContext);
    }
}
