<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Instantiator\InstantiatorInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class Object implements ComplexNestedProcessorInterface
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
}
