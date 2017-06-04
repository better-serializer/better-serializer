<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

/**
 * Interface SerializationTypeInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface SerializationTypeInterface
{

    /**
     * Get the value of the enumerator
     *
     * @return string
     */
    public function getValue();
}
