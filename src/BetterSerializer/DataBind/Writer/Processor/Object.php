<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
final class Object implements ObjectProcessorInterface
{

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

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
}
