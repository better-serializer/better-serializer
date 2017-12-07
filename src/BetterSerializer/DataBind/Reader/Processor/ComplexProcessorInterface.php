<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

/**
 * Interface ObjectProcessorInterface
 * @package BetterSerializer\DataBind\Reader\Processor
 */
interface ComplexProcessorInterface extends ProcessorInterface
{

    /**
     * @return void
     */
    public function resolveRecursiveProcessors(): void;
}