<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

/**
 *
 */
interface NamingStrategyInterface
{

    /**
     * Get the value of the enumerator
     *
     * @return null|bool|int|float|string
     */
    public function getValue();
}
