<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader;

use BetterSerializer\Common\SerializationTypeInterface;

/**
 *
 */
interface ReaderInterface
{

    /**
     * @param $serialized
     * @param string $typeString
     * @param SerializationTypeInterface $serializationType
     * @return mixed
     */
    public function readValue($serialized, string $typeString, SerializationTypeInterface $serializationType);
}
