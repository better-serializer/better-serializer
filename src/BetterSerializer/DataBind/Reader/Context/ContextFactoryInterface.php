<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context;

use BetterSerializer\Common\SerializationTypeInterface;
use RuntimeException;

/**
 *
 */
interface ContextFactoryInterface
{
    /**
     * @param mixed $serialized
     * @param SerializationTypeInterface $serializationType
     * @return ContextInterface
     */
    public function createContext($serialized, SerializationTypeInterface $serializationType): ContextInterface;
}
