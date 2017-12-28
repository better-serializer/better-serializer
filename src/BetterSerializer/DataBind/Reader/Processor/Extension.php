<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;

/**
 *
 */
final class Extension implements ProcessorInterface
{

    /**
     * @var TypeExtensionInterface
     */
    private $objectExtension;

    /**
     * @param TypeExtensionInterface $objectExtension
     */
    public function __construct(TypeExtensionInterface $objectExtension)
    {
        $this->objectExtension = $objectExtension;
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $value = $this->objectExtension->extractData($context);
        $context->setDeserialized($value);
    }
}
