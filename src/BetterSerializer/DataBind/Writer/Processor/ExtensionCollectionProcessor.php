<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 *
 */
final class ExtensionCollectionProcessor implements PropertyProcessorInterface
{

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var CollectionExtensionInterface
     */
    private $collectionExtension;

    /**
     * @param ProcessorInterface $processor
     * @param CollectionExtensionInterface $collectionExtension
     */
    public function __construct(
        ProcessorInterface $processor,
        CollectionExtensionInterface $collectionExtension
    ) {
        $this->processor = $processor;
        $this->collectionExtension = $collectionExtension;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        if ($this->collectionExtension->isEmpty($data)) {
            return;
        }

        foreach ($this->collectionExtension->getAdapter($data)->getIterator() as $key => $value) {
            $subContext = $context->createSubContext();
            $this->processor->process($subContext, $value);
            $context->mergeSubContext($key, $subContext);
        }
    }

    /**
     *
     */
    public function resolveRecursiveProcessors(): void
    {
        if (!$this->processor instanceof CachedProcessorInterface) {
            return;
        }

        $this->processor = $this->processor->getProcessor();

        if ($this->processor instanceof PropertyProcessorInterface) {
            $this->processor->resolveRecursiveProcessors();
        }
    }
}
