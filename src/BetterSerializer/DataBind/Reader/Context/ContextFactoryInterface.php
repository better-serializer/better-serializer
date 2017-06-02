<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context;

use BetterSerializer\Common\SerializationType;
use RuntimeException;

/**
 * Class ContextFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Context
 */
interface ContextFactoryInterface
{
    /**
     * @param string $serialized
     * @param SerializationType $serializationType
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function createContext(string $serialized, SerializationType $serializationType): ContextInterface;
}
