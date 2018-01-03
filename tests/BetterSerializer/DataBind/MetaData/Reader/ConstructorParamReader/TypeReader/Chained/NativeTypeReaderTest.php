<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use PHPUnit\Framework\TestCase;
use ReflectionType;

/**
 *
 */
class NativeTypeReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $reflType = $this->getMockBuilder(ReflectionType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $declaringClass = $this->createMock(ReflectionClassInterface::class);
        $param = $this->createMock(ReflectionParameterInterface::class);
        $param->expects(self::once())
            ->method('getType')
            ->willReturn($reflType);
        $param->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($declaringClass);

        $type = $this->createMock(TypeInterface::class);
        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringTypeParser->expects(self::once())
            ->method('parseWithParentContext')
            ->with((string) $reflType, $declaringClass)
            ->willReturn($stringType);

        $nativeTypeFactory = $this->createMock(NativeTypeFactoryInterface::class);
        $nativeTypeFactory->expects(self::once())
            ->method('getType')
            ->with($stringType)
            ->willReturn($type);


        $reader = new NativeTypeReader($stringTypeParser, $nativeTypeFactory);
        $reader->initialize($constructor);
        $type = $reader->getType($param);

        self::assertInstanceOf(TypeInterface::class, $type);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetTypeReturnsUnknownTypeOnParamTypeMissing(): void
    {
        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $param = $this->createMock(ReflectionParameterInterface::class);
        $param->expects(self::once())
            ->method('getType')
            ->willReturn(null);

        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $nativeTypeFactory = $this->createMock(NativeTypeFactoryInterface::class);

        $reader = new NativeTypeReader($stringTypeParser, $nativeTypeFactory);
        $reader->initialize($constructor);
        $type = $reader->getType($param);

        self::assertInstanceOf(UnknownType::class, $type);
    }
}
