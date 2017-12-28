<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;

/**
 * This processor shouldn't exist, a Simple processor should be injected into a Property procesor instead.
 * But this implementation is more performant, since there is no unnecessary sub context manipulation
 */
final class SimpleProperty extends NestedProcessor
{

    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * Property constructor.
     * @param InjectorInterface $injector
     * @param ConverterInterface $converter
     * @param string $inputKey
     */
    public function __construct(InjectorInterface $injector, ConverterInterface $converter, string $inputKey)
    {
        $this->injector = $injector;
        $this->converter = $converter;
        parent::__construct($inputKey);
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $value = $context->getValue($this->inputKey);
        $convertedValue = $this->converter->convert($value);
        $this->injector->inject($context->getDeserialized(), $convertedValue);
    }
}
