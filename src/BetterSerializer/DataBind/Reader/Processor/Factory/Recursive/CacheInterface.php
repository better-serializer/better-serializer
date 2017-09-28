<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\Recursive;

use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 * Class Cache
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\Recursive
 */
interface CacheInterface
{
    /**
     * @param string $key
     * @param ProcessorInterface $item
     */
    public function setProcessor(string $key, ProcessorInterface $item): void;

    /**
     * @param string $key
     * @return ProcessorInterface
     */
    public function getProcessor(string $key): ?ProcessorInterface;
}
