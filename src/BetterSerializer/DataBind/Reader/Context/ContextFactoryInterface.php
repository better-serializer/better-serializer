<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context;

use BetterSerializer\Common\SerializationTypeInterface;
use RuntimeException;

/**
 * Class ContextFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Context
 */
interface ContextFactoryInterface
{
    /**
     * @param string $serialized
     * @param SerializationTypeInterface $serializationType
     * @return ContextInterface
     * @throws RuntimeException
     */
    public function createContext(string $serialized, SerializationTypeInterface $serializationType): ContextInterface;
}
