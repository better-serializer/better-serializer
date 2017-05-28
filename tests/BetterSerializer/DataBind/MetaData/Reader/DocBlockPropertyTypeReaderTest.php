<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
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
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     */
    public function testGetTypeFromVarDocBlock(): void
    {
        $varTagStub = Mockery::mock(DocBlock\Tags\Var_::class)
            ->shouldReceive('getType')
            ->once()
            ->andReturn('string')
            ->getMock();

        $docBlockStub = Mockery::mock(new DocBlock())
            ->shouldReceive('getTagsByName')
            ->once()
            ->andReturn([$varTagStub])
            ->getMock();

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class)
            ->shouldReceive('create')
            ->once()
            ->andReturnValues([
                $docBlockStub,
                $docBlockStub
            ])
            ->getMock();
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);
        $reflPropertyStub->shouldReceive('getDocComment')
            ->once()
            ->andReturn('/** @var string  */')
            ->getMock();

        /* @var $contextStub Mockery\MockInterface */
        $contextStub = Mockery::mock(PropertyContextInterface::class);
        $contextStub->shouldReceive('getReflectionProperty')
            ->once()
            ->andReturn($reflPropertyStub)
            ->getMock();
        /* @var $contextStub PropertyContextInterface */

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactoryStub);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertInstanceOf(StringTypedPropertyContext::class, $typedContext);
        self::assertSame('string', $typedContext->getStringType());
    }

    /**
     *
     */
    public function testGetTypeWithoutDocBlock(): void
    {
        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);
        $reflPropertyStub->shouldReceive('getDocComment')
            ->once()
            ->andReturn('')
            ->getMock();

        /* @var $contextStub Mockery\MockInterface */
        $contextStub = Mockery::mock(PropertyContextInterface::class);
        $contextStub->shouldReceive('getReflectionProperty')
            ->once()
            ->andReturn($reflPropertyStub)
            ->getMock();
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
        $docBlockStub = Mockery::mock(new DocBlock(), ['getTagsByName' => []]);

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class, ['create' => $docBlockStub]);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        $reflPropertyStub = Mockery::mock(ReflectionProperty::class);
        $reflPropertyStub->shouldReceive('getDocComment')
            ->once()
            ->andReturn('/** @global */')
            ->getMock();

        /* @var $contextStub Mockery\MockInterface */
        $contextStub = Mockery::mock(PropertyContextInterface::class);
        $contextStub->shouldReceive('getReflectionProperty')
            ->once()
            ->andReturn($reflPropertyStub)
            ->getMock();
        /* @var $contextStub PropertyContextInterface */

        $typeReader = new DocBlockPropertyTypeReader($docBlockFactoryStub);
        $typedContext = $typeReader->resolveType($contextStub);

        self::assertNull($typedContext);
    }
}
