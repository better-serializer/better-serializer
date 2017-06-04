<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Context;

use BetterSerializer\Common\SerializationType;
use RuntimeException;

/**
 * Class ContextFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Context
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
