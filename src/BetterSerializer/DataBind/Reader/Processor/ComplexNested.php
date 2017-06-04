<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;

/**
 * Class ObjectProperty
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class ComplexNested extends NestedProcessor
{

    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @var ComplexNestedProcessorInterface
     */
    private $complexNestedProcessor;

    /**
     * Property constructor.
     * @param InjectorInterface $injector
     * @param ComplexNestedProcessorInterface $complNestedProcessor
     * @param string $inputKey
     */
    public function __construct(
        InjectorInterface $injector,
        ComplexNestedProcessorInterface $complNestedProcessor,
        string $inputKey
    ) {
        parent::__construct($inputKey);
        $this->injector = $injector;
        $this->complexNestedProcessor = $complNestedProcessor;
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $subContext = $context->readSubContext($this->inputKey);

        if (!$subContext) {
            return;
        }

        $this->complexNestedProcessor->process($subContext);
        $deserialized = $context->getDeserialized();
        $this->injector->inject($deserialized, $subContext->getDeserialized());
    }
}
