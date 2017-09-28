<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ComplexParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\RadioInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class StandardConstructorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
class StandardInstantiatorTest extends TestCase
{

    /**
     *
     */
    public function testConstruct(): void
    {
        $radio = $this->getMockBuilder(RadioInterface::class)->getMock();
        $car = $this->getMockBuilder(CarInterface::class)->getMock();

        $nativeReflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->expects(self::once())
            ->method('newInstanceArgs')
            ->willReturn($car);

        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getNativeReflClass')
            ->willReturn($nativeReflClass);

        $context = $this->createMock(ContextInterface::class);

        $paramProcessor = $this->createMock(ParamProcessorInterface::class);
        $paramProcessor->expects(self::once())
            ->method('processParam')
            ->with($context)
            ->willReturn($radio);

        $instantiator = new StandardInstantiator($reflClass, [$paramProcessor]);
        $instantiated = $instantiator->instantiate($context);

        self::assertSame($car, $instantiated);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $nativeReflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getNativeReflClass')
            ->willReturn($nativeReflClass);

        $paramProcessor = $this->createMock(ComplexParamProcessorInterface::class);
        $paramProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $instantiator = new StandardInstantiator($reflClass, [$paramProcessor]);
        $instantiator->resolveRecursiveProcessors();

        // test lazyness
        $instantiator->resolveRecursiveProcessors();
    }

    /**
     *
     */
    public function testUnserializeResolvesNativeReflectionClass(): void
    {
        $nativeReflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::atLeast(1))
            ->method('getNativeReflClass')
            ->willReturn($nativeReflClass);

        $paramProcessor = $this->createMock(ComplexParamProcessorInterface::class);

        $instantiator = new StandardInstantiator($reflClass, [$paramProcessor]);
        $serialized = serialize($instantiator);
        unserialize($serialized);
    }
}
