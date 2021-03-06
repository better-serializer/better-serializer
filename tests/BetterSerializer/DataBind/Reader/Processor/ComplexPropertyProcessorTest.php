<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ComplexPropertyProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $inputKey = 'key';
        $deserialized = $this->createMock(CarInterface::class);
        $deserialized2 = 'test';
        $subContextMock = $this->createMock(ContextInterface::class);
        $subContextMock->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($deserialized2);

        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('readSubContext')
            ->with($inputKey)
            ->willReturn($subContextMock);
        $contextMock->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($deserialized);

        $injectorMock = $this->createMock(InjectorInterface::class);
        $injectorMock->expects(self::once())
            ->method('inject')
            ->with($deserialized, $deserialized2);

        $complexNestedMock = $this->createMock(PropertyProcessorInterface::class);
        $complexNestedMock->expects(self::once())
            ->method('process')
            ->with($contextMock);

        $processor = new ComplexPropertyProcessor($injectorMock, $complexNestedMock, $inputKey);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testProcessNull(): void
    {
        $inputKey = 'key';
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('readSubContext')
            ->with($inputKey)
            ->willReturn(null);

        $injectorMock = $this->createMock(InjectorInterface::class);

        $complexNestedMock = $this->createMock(PropertyProcessorInterface::class);
        $complexNestedMock->expects(self::exactly(0))
            ->method('process');

        $processor = new ComplexPropertyProcessor($injectorMock, $complexNestedMock, $inputKey);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $inputKey = 'key';
        $subProcessor = $this->createMock(PropertyProcessorInterface::class);
        $subProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $injectorMock = $this->createMock(InjectorInterface::class);

        $processor = new ComplexPropertyProcessor($injectorMock, $processorMock, $inputKey);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unexpected processor instance: [a-zA-Z0-9_\\]+/
     */
    public function testConstructionThrowsRuntimeException(): void
    {
        $inputKey = 'key';
        $processorMock = $this->createMock(ProcessorInterface::class);
        $injectorMock = $this->createMock(InjectorInterface::class);

        new ComplexPropertyProcessor($injectorMock, $processorMock, $inputKey);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unexpected processor instance: [a-zA-Z0-9_\\]+/
     */
    public function testResolveRecursiveProcessorsThrowsRuntimeException(): void
    {
        $inputKey = 'key';
        $subProcessor = $this->createMock(ProcessorInterface::class);

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $injectorMock = $this->createMock(InjectorInterface::class);

        $processor = new ComplexPropertyProcessor($injectorMock, $processorMock, $inputKey);
        $processor->resolveRecursiveProcessors();
    }
}
