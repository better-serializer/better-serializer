<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Dto\RadioInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class StandardConstructorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor
 */
class StandardConstructorTest extends TestCase
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
        $context->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($radio);

        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $processor->expects(self::once())
            ->method('process')
            ->with($context);

        /* @var $reflClass ReflectionClass */
        /* @var $context ContextInterface */
        $constructor = new StandardConstructor($reflClass, [$processor]);
        $constructed = $constructor->construct($context);

        self::assertSame($car, $constructed);
    }
}
