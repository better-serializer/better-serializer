<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Property;

use BetterSerializer\Dto\CarInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * Class ReflectionInjectorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector\Property
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionInjectorTest extends TestCase
{

    /**
     *
     */
    public function testInject(): void
    {
        $objectStub = $this->createMock(CarInterface::class);
        $value = 'red';

        $nativeReflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $nativeReflProperty->expects(self::once())
            ->method('setValue')
            ->with($objectStub, $value);

        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty);

        $injector = new ReflectionInjector($reflPropertyStub);
        $injector->inject($objectStub, $value);
    }
}
