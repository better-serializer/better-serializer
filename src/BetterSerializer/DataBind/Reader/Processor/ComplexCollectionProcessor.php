<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use Iterator;

/**
 *
 */
final class ComplexCollectionProcessor implements CollectionProcessorInterface, PropertyProcessorInterface
{

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Object constructor.
     * @param ProcessorInterface $processor
     */
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param ContextInterface $context
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function process(ContextInterface $context): void
    {
        $data = $context->getCurrentValue();
        $deserialized = [];

        if (empty($data)) {
            $context->setDeserialized($deserialized);
            return;
        }

        /* @var $data Iterator */
        foreach ($data as $key => $value) {
            $subContext = $context->readSubContext($key);
            $this->processor->process($subContext);
            $deserialized[$key] = $subContext->getDeserialized();
        }

        $context->setDeserialized($deserialized);
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
