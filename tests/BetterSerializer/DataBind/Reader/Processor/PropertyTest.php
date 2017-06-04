<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
class PropertyTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $instance = $this->getMockBuilder(CarInterface::class)->getMock();
        $injectedValue = 5;
        $inputKey = 'test';

        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
                    ->method('getValue')
                    ->with($inputKey)
                    ->willReturn($injectedValue);
        $contextMock->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($instance);

        $injectorMock = $this->getMockBuilder(InjectorInterface::class)->getMock();
        $injectorMock->expects(self::once())
                      ->method('inject')
                      ->with($instance, $injectedValue);

        /* @var $injectorMock InjectorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new Property($injectorMock, $inputKey);
        $processor->process($contextMock);
    }
}
