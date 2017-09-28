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
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class Property extends NestedProcessor
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
