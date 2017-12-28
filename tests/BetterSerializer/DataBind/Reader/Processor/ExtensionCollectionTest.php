<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\Common\CollectionAdapterInterface;
use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\Dto\CarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ExtensionCollectionTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $instance = $this->createMock(CarInterface::class);
        $key1 = 0;
        $key2 = 1;

        $arrayData = [
            $key1 => [],
            $key2 => [],
        ];

        $collection = $this->createMock(Collection::class);

        $subContextMock = $this->createMock(ContextInterface::class);
        $subContextMock->expects(self::exactly(2))
            ->method('getDeserialized')
            ->willReturn($instance);
        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($arrayData);
        $contextMock->expects(self::exactly(2))
            ->method('readSubContext')
            ->withConsecutive([$key1], [$key2])
            ->willReturn($subContextMock);
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($collection);
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(2))
            ->method('process')
            ->withConsecutive([$subContextMock], [$subContextMock]);

        $adapterMock = $this->createMock(CollectionAdapterInterface::class);
        $adapterMock->expects(self::exactly(2))
            ->method('offsetSet')
            ->withConsecutive([$key1, $instance], [$key2, $instance]);
        $adapterMock->expects(self::once())
            ->method('getCollection')
            ->willReturn($collection);

        $extensionMock = $this->createMock(CollectionExtensionInterface::class);
        $extensionMock->expects(self::once())
            ->method('getNewAdapter')
            ->willReturn($adapterMock);

        $processor = new ExtensionCollection($processorMock, $extensionMock);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testProcessEmpty(): void
    {
        $arrayData = [];
        $collection = $this->createMock(Collection::class);

        $contextMock = $this->createMock(ContextInterface::class);
        $contextMock->expects(self::once())
            ->method('getCurrentValue')
            ->willReturn($arrayData);
        $contextMock->expects(self::exactly(0))
            ->method('readSubContext');
        $contextMock->expects(self::once())
            ->method('setDeserialized')
            ->with($collection);
        $processorMock = $this->createMock(ProcessorInterface::class);
        $processorMock->expects(self::exactly(0))
            ->method('process');

        $adapterMock = $this->createMock(CollectionAdapterInterface::class);
        $adapterMock->expects(self::once())
            ->method('getCollection')
            ->willReturn($collection);

        $extensionMock = $this->createMock(CollectionExtensionInterface::class);
        $extensionMock->expects(self::once())
            ->method('getNewAdapter')
            ->willReturn($adapterMock);

        $processor = new ExtensionCollection($processorMock, $extensionMock);
        $processor->process($contextMock);
    }

    /**
     *
     */
    public function testResolveRecursiveProcessors(): void
    {
        $subProcessor = $this->createMock(PropertyProcessorInterface::class);
        $subProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $processorMock = $this->createMock(CachedProcessorInterface::class);
        $processorMock->expects(self::once())
            ->method('getProcessor')
            ->willReturn($subProcessor);

        $extensionMock = $this->createMock(CollectionExtensionInterface::class);

        $processor = new ExtensionCollection($processorMock, $extensionMock);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }
}
