<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\Common\CustomTypeExtensionInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;

/**
 *
 */
final class CustomType implements ProcessorInterface
{

    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @var CustomTypeExtensionInterface
     */
    private $objectExtension;

    /**
     * @var string
     */
    private $outputKey;

    /**
     * @param InjectorInterface $injector,
     * @param CustomTypeExtensionInterface $objectExtension
     * @param string $outputKey
     */
    public function __construct(
        InjectorInterface $injector,
        CustomTypeExtensionInterface $objectExtension,
        string $outputKey
    ) {
        $this->injector = $injector;
        $this->objectExtension = $objectExtension;
        $this->outputKey = $outputKey;
    }

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void
    {
        $value = $this->objectExtension->extractData($context, $this->outputKey);
        $this->injector->inject($context->getDeserialized(), $value);
    }
}
