<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Context;

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
     * @param SerializationType $serializationType
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function createContext(SerializationType $serializationType): ContextInterface;
}
