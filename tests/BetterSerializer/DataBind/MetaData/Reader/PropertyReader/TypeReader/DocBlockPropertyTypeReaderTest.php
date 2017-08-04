<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringFormTypedPropertyContext;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

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

        $reflPropertyStub = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @var string  */');

        $contextStub = $this->getMockBuilder(PropertyContextInterface::class)->getMock();
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);
        /* @var $contextStub PropertyContextInterface */

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactory);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertInstanceOf(StringFormTypedPropertyContext::class, $typedContext);
        self::assertSame('string', $typedContext->getStringType());
    }

    /**
     *
     */
    public function testGetTypeWithoutDocBlock(): void
    {
        $docBlockFactoryStub = $this->getMockBuilder(DocBlockFactoryInterface::class)->getMock();
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        $reflPropertyStub = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('');

        $contextStub = $this->getMockBuilder(PropertyContextInterface::class)->getMock();
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);
        /* @var $contextStub PropertyContextInterface */

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

        $reflPropertyStub = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @global */');

        $contextStub = $this->getMockBuilder(PropertyContextInterface::class)->getMock();
        $contextStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);
        /* @var $contextStub PropertyContextInterface */

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactory);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertNull($typedContext);
    }
}
