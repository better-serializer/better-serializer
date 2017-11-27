<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\Common\CustomTypeExtensionInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 *
 */
final class CustomType implements ProcessorInterface
{

    /**
     * @var CustomTypeExtensionInterface
     */
    private $objectExtension;

    /**
     * @var string
     */
    private $outputKey;

    /**
     * @param CustomTypeExtensionInterface $objectExtension
     * @param string $outputKey
     */
    public function __construct(CustomTypeExtensionInterface $objectExtension, string $outputKey)
    {
        $this->objectExtension = $objectExtension;
        $this->outputKey = $outputKey;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $data
     */
    public function process(ContextInterface $context, $data): void
    {
        $this->objectExtension->appendData($context, $this->outputKey, $data);
    }
}
