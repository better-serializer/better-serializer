<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use ReflectionClass;
use ReflectionProperty;
use LogicException;
use RuntimeException;

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

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->once()
            ->andReturnValues([
                new StringType(),
            ])
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new DocBlockPropertyTypeReader($docBlockFactoryStub, $typeFactoryStub);

        $declaringReflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getNamespaceName' => 'TestNamespace']
        );

        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            [
                'getDocComment' => '/** @var string  */',
                'getDeclaringClass' => $declaringReflClassStub,
                'setAccessible' => null,
            ]
        );

        $type = $reader->getType($reflPropertyStub);

        self::assertInstanceOf(StringType::class, $type);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /You need to add a docblock to property "[A-Za-z0-9_]+"/
     */
    public function testGetTypeWithoutDocBlock(): void
    {
        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new DocBlockPropertyTypeReader($docBlockFactoryStub, $typeFactoryStub);

        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            ['getName' => 'property1', 'getDocComment' => '']
        );

        /* @var $reflPropertyStub ReflectionProperty */
        $reader->getType($reflPropertyStub);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /You need to add an @var annotation to property "[a-zA-Z0-9]+"/
     */
    public function testGetTypeWithoutVarTag(): void
    {
        $docBlockStub = Mockery::mock(new DocBlock(), ['getTagsByName' => []]);

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class, ['create' => $docBlockStub]);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->times(0)
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new DocBlockPropertyTypeReader($docBlockFactoryStub, $typeFactoryStub);

        $declaringReflClassStub = Mockery::mock(
            ReflectionClass::class,
            ['getNamespaceName' => 'TestNamespace', 'getName' => 'TestClass']
        );

        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            [
                'getName' => 'property1',
                'getDocComment' => '/** @global */',
                'getDeclaringClass' => $declaringReflClassStub
            ]
        );

        $reader->getType($reflPropertyStub);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - 'mixed'/
     */
    public function testGetTypeWithMixedVarTag(): void
    {
        $varTagStub = Mockery::mock(DocBlock\Tags\Var_::class)
            ->shouldReceive('getType')
            ->andReturn('mixed')
            ->getMock();

        $docBlockStub = Mockery::mock(new DocBlock(), ['getTagsByName' => [$varTagStub]]);

        /* @var $docBlockFactoryStub Mockery\MockInterface */
        $docBlockFactoryStub = Mockery::mock(DocBlockFactoryInterface::class, ['create' => $docBlockStub]);
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        /* @var $typeFactoryStub Mockery\MockInterface */
        $typeFactoryStub = Mockery::mock(TypeFactoryInterface::class);
        $typeFactoryStub->shouldReceive('getType')
            ->andThrow(LogicException::class, "Unknown type - 'mixed'")
            ->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $reader = new DocBlockPropertyTypeReader($docBlockFactoryStub, $typeFactoryStub);

        $reflClassHelperStub = Mockery::mock(
            ReflectionClass::class,
            ['getNamespaceName' => 'TestNamespace', 'getName' => 'TestClass']
        );

        $reflPropertyStub = Mockery::mock(
            ReflectionProperty::class,
            [
                'getName' => 'property1',
                'getDocComment' => '/** @var mixed */',
                'getDeclaringClass' => $reflClassHelperStub,
            ]
        );

        $reader->getType($reflPropertyStub);
    }
}
