<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator;

/**
 * Interface ProcessingInstantiatorInterface
 * @package BetterSerializer\DataBind\Reader\Instantiator
 */
interface ProcessingInstantiatorInterface extends InstantiatorInterface
{

    /**
     * @return void
     */
    public function resolveRecursiveProcessors(): void;
}
