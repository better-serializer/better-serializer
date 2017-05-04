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
final class Object implements ProcessorInterface
{

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @var string
     */
    private $outputKey;

    /**
     * Object constructor.
     * @param ProcessorInterface[] $processors
     * @param string $outputKey
     */
    public function __construct(array $processors, $outputKey = '')
    {
        $this->processors = $processors;
        $this->outputKey = $outputKey;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $instance
     */
    public function process(ContextInterface $context, $instance): void
    {
        if ($instance === null) {
            $context->write($this->outputKey, null);

            return;
        }

        $subContext = $context->createSubContext();

        foreach ($this->processors as $processor) {
            $processor->process($subContext, $instance);
        }

        $context->mergeSubContext($this->outputKey, $subContext);
    }
}
