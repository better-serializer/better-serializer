<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
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

        $context = $this->getMockBuilder(ContextInterface::class)->getMock();

        $paramProcessor = $this->getMockBuilder(ParamProcessorInterface::class)->getMock();
        $paramProcessor->expects(self::once())
            ->method('processParam')
            ->with($context)
            ->willReturn($radio);

        /* @var $reflClass ReflectionClass */
        /* @var $context ContextInterface */
        $constructor = new StandardInstantiator($reflClass, [$paramProcessor]);
        $constructed = $constructor->instantiate($context);

        self::assertSame($car, $constructed);
    }
}
