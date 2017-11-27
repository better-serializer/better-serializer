<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
final class Object implements ComplexProcessorInterface
{

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @var bool
     */
    private $resolved = false;

    /**
     * Object constructor.
     * @param ProcessorInterface[] $processors
     */
    public function __construct(array $processors)
    {
        $this->processors = $processors;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($context, $data);
        }
    }

    /**
     *
     */
    public function resolveRecursiveProcessors(): void
    {
        if ($this->resolved) {
            return;
        }

        $processors = [];

        foreach ($this->processors as $processor) {
            if ($processor instanceof CachedProcessorInterface) {
                $processor = $processor->getProcessor();
            }

            if ($processor instanceof ComplexProcessorInterface) {
                $processor->resolveRecursiveProcessors();
            }

            $processors[] = $processor;
        }

        $this->processors = $processors;
        $this->resolved = true;
    }
}
