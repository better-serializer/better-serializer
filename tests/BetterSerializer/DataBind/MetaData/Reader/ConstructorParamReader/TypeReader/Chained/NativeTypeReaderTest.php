<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionParameter;

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
        $namespace = 'test';

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn($namespace);

        $param = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param->expects(self::once())
            ->method('getType')
            ->willReturn('int');

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $nativeTypeFactory = $this->getMockBuilder(NativeTypeFactoryInterface::class)->getMock();
        $nativeTypeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        /* @var $nativeTypeFactory NativeTypeFactoryInterface */
        /* @var $constructor ReflectionMethod */
        /* @var $param ReflectionParameter */

        $reader = new NativeTypeReader($nativeTypeFactory);
        $reader->initialize($constructor);
        $type = $reader->getType($param);

        self::assertInstanceOf(TypeInterface::class, $type);
    }
}
