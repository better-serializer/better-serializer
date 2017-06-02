<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Property;

use BetterSerializer\Dto\CarInterface;
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
        $objectStub = $this->getMockBuilder(CarInterface::class)->getMock();
        $value = 'red';

        $reflPropertyStub = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $reflPropertyStub->expects(self::once())
            ->method('setValue')
            ->with($objectStub, $value);

        /* @var $reflPropertyStub ReflectionProperty */
        $injector = new ReflectionInjector($reflPropertyStub);
        $injector->inject($objectStub, $value);
    }
}
