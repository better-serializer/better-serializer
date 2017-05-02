<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;

/**
 * Class ProcessorInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor
 */
interface ProcessorInterface
{

    /**
     * @param ContextInterface $context
     * @param mixed $instance
     */
    public function process(ContextInterface $context, $instance): void;
}
