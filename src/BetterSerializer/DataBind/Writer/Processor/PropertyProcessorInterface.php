<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

/**
 * Interface ObjectProcessorInterface
 * @package BetterSerializer\DataBind\Writer\Processor
 */
interface PropertyProcessorInterface extends ProcessorInterface
{

    /**
     * @return void
     */
    public function resolveRecursiveProcessors(): void;
}
