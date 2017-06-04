<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use Iterator;

/**
 * Class Object
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
final class SimpleCollection implements CollectionProcessorInterface
{

    /**
     * @param ContextInterface $context
     * @internal param mixed $data
     */
    public function process(ContextInterface $context): void
    {
        $data = $context->getCurrentValue();
        $deserialized = [];

        if (empty($data)) {
            $context->setDeserialized($deserialized);
            return;
        }

        /* @var $data Iterator */
        foreach ($data as $key => $value) {
            $deserialized[$key] = $value;
        }

        $context->setDeserialized($deserialized);
    }
}
