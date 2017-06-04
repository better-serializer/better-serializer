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
 * Class NestedObjectTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
class ComplexNestedTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $inputKey = 'key';
        $deserialized = $this->getMockBuilder(CarInterface::class)->getMock();
        $deserialized2 = 'test';
        $subContextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $subContextMock->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($deserialized2);

        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('readSubContext')
            ->with($inputKey)
            ->willReturn($subContextMock);
        $contextMock->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($deserialized);

        $injectorMock = $this->getMockBuilder(InjectorInterface::class)->getMock();
        $injectorMock->expects(self::once())
            ->method('inject')
            ->with($deserialized, $deserialized2);

        $complexNestedMock = $this->getMockBuilder(ComplexNestedProcessorInterface::class)->getMock();
        $complexNestedMock->expects(self::once())
            ->method('process')
            ->with($contextMock);

        /* @var $injectorMock InjectorInterface */
        /* @var $complexNestedMock ComplexNestedProcessorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new ComplexNested($injectorMock, $complexNestedMock, $inputKey);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testProcessNull(): void
    {
        $inputKey = 'key';
        $contextMock = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextMock->expects(self::once())
            ->method('readSubContext')
            ->with($inputKey)
            ->willReturn(null);

        $injectorMock = $this->getMockBuilder(InjectorInterface::class)->getMock();

        $complexNestedMock = $this->getMockBuilder(ComplexNestedProcessorInterface::class)->getMock();
        $complexNestedMock->expects(self::exactly(0))
            ->method('process');

        /* @var $injectorMock InjectorInterface */
        /* @var $complexNestedMock ComplexNestedProcessorInterface */
        /* @var $contextMock ContextInterface */
        $processor = new ComplexNested($injectorMock, $complexNestedMock, $inputKey);
        $processor->process($contextMock);
    }
}
