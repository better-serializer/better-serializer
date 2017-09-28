<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use PHPUnit\Framework\TestCase;
use ReflectionType;

/**
 * Class NativeTypeReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader\Chained
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

        $param = $this->createMock(ReflectionParameterInterface::class);
        $param->expects(self::once())
            ->method('getType')
            ->willReturn($reflType);

        $type = $this->createMock(TypeInterface::class);

        $nativeTypeFactory = $this->createMock(NativeTypeFactoryInterface::class);
        $nativeTypeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $reader = new NativeTypeReader($nativeTypeFactory);
        $reader->initialize($constructor);
        $type = $reader->getType($param);

        self::assertInstanceOf(TypeInterface::class, $type);
    }
}
