<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Instantiator\ProcessingInstantiatorInterface;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class Object implements ComplexProcessorInterface
{

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

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
     * @param InstantiatorInterface $instantiator
     * @param ProcessorInterface[] $processors
     */
    public function __construct(InstantiatorInterface $instantiator, array $processors)
    {
        $this->instantiator = $instantiator;
        $this->processors = $processors;
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $instance = $this->instantiator->instantiate($context);
        $context->setDeserialized($instance);

        foreach ($this->processors as $processor) {
            $processor->process($context);
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

        if ($this->instantiator instanceof ProcessingInstantiatorInterface) {
            $this->instantiator->resolveRecursiveProcessors();
        }

        $this->resolved = true;
    }
}
