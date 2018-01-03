<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\Common\CollectionAdapterInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use PHPUnit\Framework\TestCase;
use Iterator;

/**
 *
 */
class ExtensionCollectionProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $key1 = 'k1';
        $val1 = 'v1';
        $key2 = 'k2';
        $val2 = 'v2';
        $inData = [$key1 => $val1, $key2 => $val2];
        $count = count($inData);

        $subContext = $this->createMock(ContextInterface::class);

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::exactly($count))
            ->method('createSubContext')
            ->willReturn($subContext);
        $context->expects(self::exactly($count))
            ->method('mergeSubContext')
            ->withConsecutive([$key1, $subContext], [$key2, $subContext]);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects(self::exactly($count))
            ->method('process')
            ->withConsecutive([$subContext, $val1], [$subContext, $val2]);

        $adapter = $this->createMock(CollectionAdapterInterface::class);
        $adapter->expects(self::once())
            ->method('getIterator')
            ->willReturn($this->mockIterator($inData, true));

        $extension = $this->createMock(CollectionExtensionInterface::class);
        $extension->expects(self::once())
            ->method('getAdapter')
            ->with($inData)
            ->willReturn($adapter);
        $extension->expects(self::once())
            ->method('isEmpty')
            ->with($inData)
            ->willReturn(false);

        $collectionProcessor = new ExtensionCollectionProcessor($processor, $extension);
        $collectionProcessor->process($context, $inData);
    }

    /**
     *
     */
    public function testProcessSkipEmptyCollection(): void
    {
        $inData = [];
        $count = count($inData);

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::exactly($count))
            ->method('createSubContext');
        $context->expects(self::exactly($count))
            ->method('mergeSubContext');

        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects(self::exactly($count))
            ->method('process');

        $extension = $this->createMock(CollectionExtensionInterface::class);
        $extension->expects(self::exactly(0))
            ->method('getAdapter');
        $extension->expects(self::once())
            ->method('isEmpty')
            ->with($inData)
            ->willReturn(true);

        $collectionProcessor = new ExtensionCollectionProcessor($processor, $extension);
        $collectionProcessor->process($context, $inData);
    }

    /**
     * @param array $items
     * @param bool $includeCallsToKey
     *
     * @return Iterator
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    private function mockIterator(array $items, $includeCallsToKey = false): Iterator
    {
        $iterator = $this->createMock(Iterator::class);
        $iterator->expects($this->at(0))
            ->method('rewind');
        $counter = 1;

        foreach ($items as $key => $value) {
            $iterator->expects($this->at($counter++))
                ->method('valid')
                ->will($this->returnValue(true));
            $iterator->expects($this->at($counter++))
                ->method('current')
                ->will($this->returnValue($value));

            if ($includeCallsToKey) {
                $iterator->expects($this->at($counter++))
                    ->method('key')
                    ->will($this->returnValue($key));
            }
            $iterator->expects($this->at($counter++))
                ->method('next');
        }
        $iterator->expects($this->at($counter))
            ->method('valid')
            ->will($this->returnValue(false));

        return $iterator;
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

        $extension = $this->createMock(CollectionExtensionInterface::class);

        $processor = new ExtensionCollectionProcessor($processorMock, $extension);
        $processor->resolveRecursiveProcessors();

        // lazy resolve test
        $processor->resolveRecursiveProcessors();
    }
}
