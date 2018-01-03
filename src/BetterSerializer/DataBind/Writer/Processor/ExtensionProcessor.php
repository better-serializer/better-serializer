<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 *
 */
final class ExtensionProcessor implements ProcessorInterface
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
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        $this->objectExtension->appendData($context, $data);
    }
}
