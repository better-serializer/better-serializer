<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use Iterator;

/**
 *
 */
final class ExtensionCollection implements PropertyProcessorInterface
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
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function process(ContextInterface $context): void
    {
        $data = $context->getCurrentValue();
        $adapter = $this->collectionExtension->getNewAdapter();

        if (empty($data)) {
            $context->setDeserialized($adapter->getCollection());
            return;
        }

        /* @var $data Iterator */
        foreach ($data as $key => $value) {
            $subContext = $context->readSubContext($key);
            $this->processor->process($subContext);
            $adapter[$key] = $subContext->getDeserialized();
        }

        $context->setDeserialized($adapter->getCollection());
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
