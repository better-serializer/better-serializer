<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 * Class ComplexParamProcessor
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor
 */
final class ComplexParamProcessor implements ParamProcessorInterface
{

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * ComplexParamProcessor constructor.
     * @param ProcessorInterface $processor
     */
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function processParam(ContextInterface $context)
    {
        $this->processor->process($context);

        return $context->getDeserialized();
    }
}
