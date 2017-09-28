<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\DataBind\Reader\Context\ContextInterface;

/**
 * Class ProcessorInterface
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor
 */
interface ProcessorInterface
{

    /**
     * @param ContextInterface $context
     */
    public function process(ContextInterface $context): void;
}
