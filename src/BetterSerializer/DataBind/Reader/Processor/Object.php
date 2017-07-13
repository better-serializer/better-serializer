<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Constructor\ConstructorInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class Object implements ComplexNestedProcessorInterface
{

    /**
     * @var ConstructorInterface
     */
    private $constructor;

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * Object constructor.
     * @param ConstructorInterface $constructor
     * @param ProcessorInterface[] $processors
     */
    public function __construct(ConstructorInterface $constructor, array $processors)
    {
        $this->constructor = $constructor;
        $this->processors = $processors;
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $instance = $this->constructor->construct($context);
        $context->setDeserialized($instance);

        foreach ($this->processors as $processor) {
            $processor->process($context);
        }
    }
}
