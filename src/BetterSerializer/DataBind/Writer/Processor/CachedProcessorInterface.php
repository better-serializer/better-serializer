<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

/**
 * Interface CachedProcessorInterface
 * @package BetterSerializer\DataBind\Writer\Processor
 */
interface CachedProcessorInterface extends ProcessorInterface
{

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface;
}
