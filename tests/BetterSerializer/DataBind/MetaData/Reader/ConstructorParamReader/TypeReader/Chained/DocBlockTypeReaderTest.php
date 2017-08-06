<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use phpDocumentor\Reflection\DocBlockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class DocBlockTypeReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader\Chained
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class DocBlockTypeReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetTypeReturnsNormalType(): void
    {
        $declaringClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $declaringClass->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn('test');

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($declaringClass);
        $constructor->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @param int $test */');

        $param = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param->expects(self::once())
            ->method('getName')
            ->willReturn('test');

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $docBlockFactory = DocBlockFactory::createInstance(); // final
        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        /* @var $typeFactory TypeFactoryInterface */
        /* @var $constructor ReflectionMethod */
        /* @var $param ReflectionParameter */
        $reader = new DocBlockTypeReader($typeFactory, $docBlockFactory);
        $reader->initialize($constructor);

        $retrievedType = $reader->getType($param);

        self::assertSame($type, $retrievedType);
    }

    /**
     *
     */
    public function testGetTypeReturnsUnknownType(): void
    {
        $declaringClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $declaringClass->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn('test');

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($declaringClass);
        $constructor->expects(self::once())
            ->method('getDocComment')
            ->willReturn('');

        $param = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param->expects(self::once())
            ->method('getName')
            ->willReturn('test');

        $docBlockFactory = DocBlockFactory::createInstance(); // final
        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();

        /* @var $typeFactory TypeFactoryInterface */
        /* @var $constructor ReflectionMethod */
        /* @var $param ReflectionParameter */
        $reader = new DocBlockTypeReader($typeFactory, $docBlockFactory);
        $reader->initialize($constructor);

        $retrievedType = $reader->getType($param);

        self::assertInstanceOf(UnknownType::class, $retrievedType);
    }
}
