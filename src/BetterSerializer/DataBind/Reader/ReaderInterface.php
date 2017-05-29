<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader;

use BetterSerializer\Common\SerializationType;

/**
 * Interface ReaderInterface
 * @package BetterSerializer\DataBind\Reader
 */
interface ReaderInterface
{

    /**
     * @param string $serialized
     * @param string $stringType
     * @param SerializationType $serializationType
     * @return mixed
     */
    public function readValue(string $serialized, string $stringType, SerializationType $serializationType);
}
