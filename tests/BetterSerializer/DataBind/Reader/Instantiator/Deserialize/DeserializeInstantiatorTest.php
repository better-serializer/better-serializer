<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Deserialize;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\Dto\Door;
use Doctrine\Instantiator\InstantiatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UnserializeConstructorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
class DeserializeInstantiatorTest extends TestCase
{

    /**
     *
     */
    public function testConstruct(): void
    {
        $className = Door::class;
        $instantiator = $this->getMockBuilder(InstantiatorInterface::class)
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $instantiator->expects(self::once())
            ->method('instantiate')
            ->with($className)
            ->willReturn(new Door());
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();

        /* @var $instantiator InstantiatorInterface */
        /* @var $context ContextInterface */
        $constructor = new DeserializeInstantiator($instantiator, $className);
        $object = $constructor->instantiate($context);

        self::assertInstanceOf($className, $object);
    }
}
