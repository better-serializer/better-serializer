<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use PHPUnit\Framework\TestCase;

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
        $declaringClass = $this->createMock(ReflectionClassInterface::class);

        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $constructor->expects(self::once())
            ->method('getDocComment')
            ->willReturn('/** @param int $test */');

        $param = $this->createMock(ReflectionParameterInterface::class);
        $param->expects(self::once())
            ->method('getName')
            ->willReturn('test');
        $param->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($declaringClass);

        $type = $this->createMock(TypeInterface::class);

        $docBlockFactory = DocBlockFactory::createInstance(); // final
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);

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
        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $constructor->expects(self::once())
            ->method('getDocComment')
            ->willReturn('');

        $param = $this->createMock(ReflectionParameterInterface::class);
        $param->expects(self::once())
            ->method('getName')
            ->willReturn('test');

        $docBlockFactory = DocBlockFactory::createInstance(); // final
        $typeFactory = $this->createMock(TypeFactoryInterface::class);

        $reader = new DocBlockTypeReader($typeFactory, $docBlockFactory);
        $reader->initialize($constructor);

        $retrievedType = $reader->getType($param);

        self::assertInstanceOf(UnknownType::class, $retrievedType);
    }
}
