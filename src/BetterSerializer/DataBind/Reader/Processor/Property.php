<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

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
     * Property constructor.
     * @param InjectorInterface $injector
     * @param string $inputKey
     */
    public function __construct(InjectorInterface $injector, string $inputKey)
    {
        $this->injector = $injector;
        parent::__construct($inputKey);
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $value = $context->getValue($this->inputKey);
        $this->injector->inject($context->getDeserialized(), $value);
    }
}
