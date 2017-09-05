<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ComplexParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\RadioInterface;
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

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('newInstanceArgs')
            ->willReturn($car);

        $context = $this->createMock(ContextInterface::class);

        $paramProcessor = $this->getMockBuilder(ParamProcessorInterface::class)->getMock();
        $paramProcessor->expects(self::once())
            ->method('processParam')
            ->with($context)
            ->willReturn($radio);

        /* @var $reflClass ReflectionClass */
        $instantiator = new StandardInstantiator($reflClass, [$paramProcessor]);
        $instantiated = $instantiator->instantiate($context);

        self::assertSame($car, $instantiated);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paramProcessor = $this->getMockBuilder(ComplexParamProcessorInterface::class)->getMock();
        $paramProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        /* @var $reflClass ReflectionClass */
        $instantiator = new StandardInstantiator($reflClass, [$paramProcessor]);
        $instantiator->resolveRecursiveProcessors();

        // test lazyness
        $instantiator->resolveRecursiveProcessors();
    }
}
