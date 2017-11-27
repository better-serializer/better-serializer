<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use RuntimeException;

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
     * @var ComplexProcessorInterface|CollectionProcessorInterface|CachedProcessorInterface|ProcessorInterface
     */
    private $processor;

    /**
     * Property constructor.
     * @param InjectorInterface $injector
     * @param ProcessorInterface $processor
     * @param string $inputKey
     * @throws RuntimeException
     */
    public function __construct(
        InjectorInterface $injector,
        ProcessorInterface $processor,
        string $inputKey
    ) {
        if (!$processor instanceof ComplexProcessorInterface
            && !$processor instanceof CollectionProcessorInterface
            && !$processor instanceof CachedProcessorInterface) {
            throw new RuntimeException(
                sprintf('Unexpected processor instance: %s', get_class($processor))
            );
        }

        parent::__construct($inputKey);
        $this->injector = $injector;
        $this->processor = $processor;
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

        $this->processor->process($subContext);
        $deserialized = $context->getDeserialized();
        $this->injector->inject($deserialized, $subContext->getDeserialized());
    }

    /**
     * @throws RuntimeException
     */
    public function resolveRecursiveProcessors(): void
    {
        if (!$this->processor instanceof CachedProcessorInterface) {
            return;
        }

        $this->processor = $this->processor->getProcessor();

        if (!$this->processor instanceof ComplexProcessorInterface) {
            throw new RuntimeException(
                sprintf('Unexpected processor instance: %s', get_class($this->processor))
            );
        }

        $this->processor->resolveRecursiveProcessors();
    }
}
